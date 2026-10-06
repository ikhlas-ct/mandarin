<?php

namespace App\Http\Controllers\Pelajar;

use App\Http\Controllers\Controller;
use App\Models\GrupSoal;
use App\Models\HasilUjian;
use App\Models\JawabanPelajar;
use App\Models\Kosakata;
use App\Models\Pelajar;
use App\Models\ProgresHafalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LatihanController extends Controller
{
    private function pelajar(): Pelajar
    {
        $pelajar = Auth::user()?->pelajar;
        abort_if(! $pelajar, 403, 'Akun ini belum punya profil pelajar.');

        return $pelajar;
    }

    /** Pastikan hasil ujian ini milik pelajar yang sedang login. */
    private function pastikanMilik(HasilUjian $hasilUjian, Pelajar $pelajar): void
    {
        abort_unless((int) $hasilUjian->pelajar_id === (int) $pelajar->id, 403);
    }

    /** Daftar latihan/ujian yang aktif, dengan pencarian judul dan filter jenis. */
    public function index(Request $request)
    {
        $pelajar = $this->pelajar();
        $cari    = trim((string) $request->query('search'));
        $jenis   = (string) $request->query('jenis');

        $grups = GrupSoal::aktif()
            ->has('soals')
            ->with([
                'levelHsk',
                'hasilUjians' => fn ($q) => $q->where('pelajar_id', $pelajar->id)->whereNotNull('selesai_pada'),
            ])
            ->withCount('soals')
            ->when($cari !== '', fn ($q) => $q->where('judul', 'like', "%{$cari}%"))
            ->when(
                in_array($jenis, [GrupSoal::LATIHAN, GrupSoal::UJIAN], true),
                fn ($q) => $q->where('jenis', $jenis)
            )
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('pages.pelajar.latihan.index', compact('pelajar', 'grups', 'cari', 'jenis'));
    }

    /** Detail satu latihan: info, riwayat percobaan, dan ringkasan hafalan kata-katanya. */
    public function show(GrupSoal $grupSoal)
    {
        abort_unless($grupSoal->aktif, 404);

        $pelajar = $this->pelajar();
        $grupSoal->load(['levelHsk', 'soals']);

        $riwayat = $grupSoal->hasilUjians()
            ->where('pelajar_id', $pelajar->id)
            ->whereNotNull('selesai_pada')
            ->latest('selesai_pada')
            ->get();

        $belumSelesai = $grupSoal->hasilUjians()
            ->where('pelajar_id', $pelajar->id)
            ->whereNull('selesai_pada')
            ->latest('id')
            ->first();

        $sisa = $grupSoal->sisaPercobaan($pelajar->id);

        // Ringkasan hafalan untuk kata-kata yang diujikan di grup ini.
        $kosakataIds = $grupSoal->soals->pluck('kosakata_id')->filter()->unique()->values();
        $statusKata  = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->whereIn('kosakata_id', $kosakataIds)
            ->pluck('status');

        $ringkasan = [
            'total' => $kosakataIds->count(),
            'ingat' => $statusKata->filter(fn ($s) => $s === ProgresHafalan::INGAT_SEPENUHNYA)->count(),
            'lupa'  => $statusKata->filter(fn ($s) => $s === ProgresHafalan::LUPA_DAN_INGAT)->count(),
        ];
        $ringkasan['belum'] = max(0, $ringkasan['total'] - $ringkasan['ingat'] - $ringkasan['lupa']);

        return view('pages.pelajar.latihan.show', compact(
            'pelajar', 'grupSoal', 'riwayat', 'belumSelesai', 'sisa', 'ringkasan'
        ));
    }

    /** Mulai mengerjakan (atau lanjutkan yang belum selesai). */
    public function mulai(GrupSoal $grupSoal)
    {
        abort_unless($grupSoal->aktif, 404);

        $pelajar = $this->pelajar();

        // Klik ganda / buka ulang: pakai percobaan yang belum selesai, jangan buat baru.
        $belumSelesai = HasilUjian::where('pelajar_id', $pelajar->id)
            ->where('grup_soal_id', $grupSoal->id)
            ->whereNull('selesai_pada')
            ->latest('id')
            ->first();

        if ($belumSelesai) {
            return redirect()->route('pelajar.pengerjaan.kerjakan', $belumSelesai);
        }

        if ($grupSoal->sisaPercobaan($pelajar->id) === 0) {
            return back()->with('error', 'Kesempatan mengerjakan sudah habis.');
        }

        $jumlahSoal = $grupSoal->soals()->count();
        if ($jumlahSoal === 0) {
            return back()->with('error', 'Soal di grup ini belum tersedia.');
        }

        $hasil = HasilUjian::create([
            'pelajar_id'   => $pelajar->id,
            'grup_soal_id' => $grupSoal->id,
            'jumlah_soal'  => $jumlahSoal,
            'jumlah_benar' => 0,
            'skor'         => 0,
            'mulai_pada'   => now(),
        ]);

        return redirect()->route('pelajar.pengerjaan.kerjakan', $hasil);
    }

    /** Halaman mengerjakan soal. */
    public function kerjakan(HasilUjian $hasilUjian)
    {
        $pelajar = $this->pelajar();
        $this->pastikanMilik($hasilUjian, $pelajar);

        if ($hasilUjian->selesai_pada) {
            return redirect()->route('pelajar.pengerjaan.hasil', $hasilUjian);
        }

        $hasilUjian->load('grupSoal.soals.kosakata');
        $grupSoal = $hasilUjian->grupSoal;

        // Sisa waktu (detik), null kalau grup ini tidak dibatasi durasi.
        // Batas durasi hanya ditegakkan oleh timer di browser.
        $sisaDetik = null;
        if ($grupSoal->durasi_menit) {
            $terpakai  = now()->timestamp - $hasilUjian->mulai_pada->timestamp;
            $sisaDetik = max(0, $grupSoal->durasi_menit * 60 - $terpakai);
        }

        return view('pages.pelajar.latihan.kerjakan', compact('hasilUjian', 'grupSoal', 'sisaDetik'));
    }

    /**
     * Kumpulkan jawaban: nilai, simpan, lalu OTOMATIS perbarui hafalan.
     *
     * Aturan hafalan (satu pembaruan per kata per pengerjaan):
     * - semua soal kata itu benar -> ingat_sepenuhnya, review 7 hari lagi
     * - ada yang salah            -> lupa_dan_ingat,   review besok
     * - kata yang tidak dijawab sama sekali tidak mengubah hafalan
     * Hasilnya tercatat di riwayat_reviews dengan sumber 'soal'.
     */
    public function kumpulkan(Request $request, HasilUjian $hasilUjian)
    {
        $pelajar = $this->pelajar();
        $this->pastikanMilik($hasilUjian, $pelajar);

        $request->validate([
            'jawaban'   => ['nullable', 'array'],
            'jawaban.*' => ['nullable', 'in:A,B,C,D'],
        ]);

        $input = (array) $request->input('jawaban', []);

        DB::transaction(function () use ($hasilUjian, $pelajar, $input) {
            // Kunci baris supaya submit ganda tidak menilai dua kali.
            $hasil = HasilUjian::whereKey($hasilUjian->id)->lockForUpdate()->first();

            if ($hasil->selesai_pada) {
                return;
            }

            $soals = $hasil->grupSoal->soals()->get();

            $jumlahBenar = 0;
            $totalPoin   = 0;
            $poinDidapat = 0;
            $perKata     = []; // kosakata_id => true kalau semua jawaban untuk kata itu benar

            foreach ($soals as $soal) {
                $jawab = $input[$soal->id] ?? null;
                $jawab = ($jawab === null || $jawab === '') ? null : strtoupper($jawab);

                $benar = $soal->cekJawaban($jawab);
                $poin  = $benar ? (int) $soal->poin : 0;

                $totalPoin   += (int) $soal->poin;
                $poinDidapat += $poin;
                $jumlahBenar += $benar ? 1 : 0;

                JawabanPelajar::create([
                    'hasil_ujian_id' => $hasil->id,
                    'soal_id'        => $soal->id,
                    'jawaban'        => $jawab,
                    'benar'          => $benar,
                    'poin'           => $poin,
                ]);

                if ($soal->kosakata_id && $jawab !== null) {
                    $perKata[$soal->kosakata_id] = ($perKata[$soal->kosakata_id] ?? true) && $benar;
                }
            }

            // ---- Hafalan otomatis ----
            foreach ($perKata as $kosakataId => $semuaBenar) {
                $progres = ProgresHafalan::firstOrCreate(
                    ['pelajar_id' => $pelajar->id, 'kosakata_id' => $kosakataId],
                    ['status' => ProgresHafalan::BERIKUTNYA]
                );

                $progres->catatKartu($semuaBenar, 'soal');
            }

            $hasil->update([
                'jumlah_soal'  => $soals->count(),
                'jumlah_benar' => $jumlahBenar,
                'skor'         => $totalPoin > 0 ? round($poinDidapat / $totalPoin * 100, 2) : 0,
                'selesai_pada' => now(),
            ]);
        });

        return redirect()->route('pelajar.pengerjaan.hasil', $hasilUjian);
    }

    /** Hasil: skor, pembahasan tiap soal, dan dampaknya ke hafalan. */
    public function hasil(HasilUjian $hasilUjian)
    {
        $pelajar = $this->pelajar();
        $this->pastikanMilik($hasilUjian, $pelajar);

        if (! $hasilUjian->selesai_pada) {
            return redirect()->route('pelajar.pengerjaan.kerjakan', $hasilUjian);
        }

        $hasilUjian->load(['grupSoal.levelHsk', 'jawabanPelajars.soal']);

        $jawabans = $hasilUjian->jawabanPelajars
            ->filter(fn ($j) => $j->soal)
            ->sortBy(fn ($j) => [$j->soal->urutan, $j->soal->id])
            ->values();

        // Kata yang dinilai di pengerjaan ini + status hafalannya sekarang.
        $perKata = $jawabans
            ->filter(fn ($j) => $j->soal->kosakata_id && $j->jawaban !== null)
            ->groupBy(fn ($j) => $j->soal->kosakata_id);

        $kosakatas = Kosakata::whereIn('id', $perKata->keys())->get()->keyBy('id');
        $progres   = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->whereIn('kosakata_id', $perKata->keys())
            ->get()
            ->keyBy('kosakata_id');

        $dampak = $perKata
            ->map(fn ($items, $kosakataId) => [
                'kosakata' => $kosakatas[$kosakataId] ?? null,
                'benar'    => $items->every(fn ($j) => $j->benar),
                'progres'  => $progres[$kosakataId] ?? null,
            ])
            ->filter(fn ($d) => $d['kosakata'])
            ->values();

        return view('pages.pelajar.latihan.hasil', compact('hasilUjian', 'jawabans', 'dampak'));
    }
}
