<?php

namespace App\Http\Controllers\Pelajar;

use App\Http\Controllers\Controller;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use App\Models\ProgresHafalan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $pelajar = $user->pelajar;

        abort_if(! $pelajar, 403, 'Akun ini belum memiliki data pelajar.');

        // Model WebsiteSetting tidak ada di file yang dikirim, jadi dicek dulu.
        $setting = class_exists(\App\Models\WebsiteSetting::class)
            ? \App\Models\WebsiteSetting::first()
            : null;

        // ── Hafalan per status ───────────────────────────────────────
        $perStatus = $pelajar->progresHafalans()
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $ingat    = (int) ($perStatus[ProgresHafalan::INGAT_SEPENUHNYA] ?? 0);
        $lupa     = (int) ($perStatus[ProgresHafalan::LUPA_DAN_INGAT] ?? 0);
        $berikut  = (int) ($perStatus[ProgresHafalan::BERIKUTNYA] ?? 0);
        $totalKosakata = Kosakata::count();

        // ── Review hari ini & keseluruhan ────────────────────────────
        $hariIni = $pelajar->riwayatReviews()
            ->whereBetween('direview_pada', [now()->startOfDay(), now()->endOfDay()]);

        $reviewHariIni = (clone $hariIni)->count();
        $benarHariIni  = (clone $hariIni)->where('benar', true)->count();

        $totalReview = $pelajar->riwayatReviews()->count();
        $totalBenar  = $pelajar->riwayatReviews()->where('benar', true)->count();

        $jatuhTempo = $pelajar->progresHafalans()->untukPopup()->jatuhTempo()->count();

        // ── Ujian (tabel bisa belum ada di database) ─────────────────
        $totalUjian = 0;
        $rataSkor   = 0;
        $hasilUjianTerbaru = collect();

        if (Schema::hasTable('hasil_ujians')) {
            $totalUjian = $pelajar->hasilUjians()->count();
            $rataSkor   = (int) round($pelajar->hasilUjians()->avg('skor') ?? 0);
            $hasilUjianTerbaru = $pelajar->hasilUjians()
                ->with('grupSoal')
                ->latest('mulai_pada')
                ->limit(5)
                ->get();
        }

        $stats = [
            'total_kosakata'   => $totalKosakata,
            'ingat'            => $ingat,
            'lupa'             => $lupa,
            'berikutnya'       => $berikut,
            'jatuh_tempo'      => $jatuhTempo,
            'review_hari_ini'  => $reviewHariIni,
            'akurasi_hari_ini' => $reviewHariIni > 0 ? round($benarHariIni / $reviewHariIni * 100) : 0,
            'total_review'     => $totalReview,
            'akurasi_total'    => $totalReview > 0 ? round($totalBenar / $totalReview * 100) : 0,
            'streak'           => $this->hitungStreak($pelajar),
            'total_ujian'      => $totalUjian,
            'rata_skor'        => $rataSkor,
        ];

        return view('pages.pelajar.dashboard', [
            'setting'             => $setting,
            'pelajar'             => $pelajar,
            'stats'               => $stats,
            'kelengkapan'         => $this->kelengkapanProfil($pelajar),
            'distribusi_status'   => $perStatus,
            'aktivitas_review'    => $this->aktivitasHarian($pelajar, 14),
            'progres_level'       => $this->progresPerLevel($pelajar),
            'kata_siap_review'    => $pelajar->progresHafalans()
                ->untukPopup()->jatuhTempo()
                ->with('kosakata')
                ->orderBy('review_berikutnya')
                ->limit(6)->get(),
            'review_terbaru'      => $pelajar->riwayatReviews()
                ->with('kosakata')
                ->latest('direview_pada')
                ->limit(8)->get(),
            'kata_sulit'          => $pelajar->riwayatReviews()
                ->where('benar', false)
                ->select('kosakata_id', DB::raw('COUNT(*) as salah'))
                ->groupBy('kosakata_id')
                ->orderByDesc('salah')
                ->with('kosakata')
                ->limit(5)->get(),
            'hasil_ujian_terbaru' => $hasilUjianTerbaru,
        ]);
    }

    /** Jumlah hari berturut-turut belajar. Tetap berjalan kalau hari ini belum review tapi kemarin sudah. */
    private function hitungStreak($pelajar): int
    {
        $hariBelajar = $pelajar->riwayatReviews()
            ->where('direview_pada', '>=', now()->subDays(365)->startOfDay())
            ->selectRaw('DATE(direview_pada) as tgl')
            ->groupBy('tgl')
            ->pluck('tgl')
            ->flip();

        $cursor = today();
        if (! isset($hariBelajar[$cursor->format('Y-m-d')])) {
            $cursor = $cursor->subDay();
        }

        $streak = 0;
        while (isset($hariBelajar[$cursor->format('Y-m-d')])) {
            $streak++;
            $cursor = $cursor->subDay();
        }

        return $streak;
    }

    /** Total review dan jumlah benar per hari, termasuk hari yang kosong. */
    private function aktivitasHarian($pelajar, int $hari): array
    {
        $data = $pelajar->riwayatReviews()
            ->where('direview_pada', '>=', now()->subDays($hari - 1)->startOfDay())
            ->selectRaw('DATE(direview_pada) as tgl, COUNT(*) as total, SUM(benar) as benar')
            ->groupBy('tgl')
            ->get()
            ->keyBy('tgl');

        $hasil = [];
        for ($i = $hari - 1; $i >= 0; $i--) {
            $tgl = today()->subDays($i);
            $row = $data[$tgl->format('Y-m-d')] ?? null;

            $hasil[] = [
                'label' => $tgl->translatedFormat('d M'),
                'total' => (int) ($row->total ?? 0),
                'benar' => (int) ($row->benar ?? 0),
            ];
        }

        return $hasil;
    }

    /** Progres hafalan di tiap level HSK (satu query untuk semua level). */
    private function progresPerLevel($pelajar): array
    {
        $rows = ProgresHafalan::where('pelajar_id', $pelajar->id)
            ->join('kosakatas', 'kosakatas.id', '=', 'progres_hafalans.kosakata_id')
            ->select('kosakatas.level_hsk_id', 'progres_hafalans.status', DB::raw('COUNT(*) as total'))
            ->groupBy('kosakatas.level_hsk_id', 'progres_hafalans.status')
            ->get()
            ->groupBy('level_hsk_id');

        return LevelHsk::withCount('kosakatas')
            ->orderBy('tingkat')
            ->get()
            ->map(function ($level) use ($rows) {
                $status = ($rows[$level->id] ?? collect())->pluck('total', 'status');
                $total  = (int) $level->kosakatas_count;
                $ingat  = (int) ($status[ProgresHafalan::INGAT_SEPENUHNYA] ?? 0);
                $lupa   = (int) ($status[ProgresHafalan::LUPA_DAN_INGAT] ?? 0);

                return [
                    'nama'        => $level->nama,
                    'total'       => $total,
                    'ingat'       => $ingat,
                    'lupa'        => $lupa,
                    'persen'      => $total > 0 ? round($ingat / $total * 100) : 0,
                    'persen_lupa' => $total > 0 ? round($lupa / $total * 100) : 0,
                ];
            })
            ->all();
    }

    /** Kelengkapan data diri pelajar. */
    private function kelengkapanProfil($pelajar): array
    {
        $kolom  = ['nama', 'no_telp', 'alamat', 'foto', 'keterangan'];
        $terisi = collect($kolom)->filter(fn ($k) => filled($pelajar->{$k}))->count();

        return [
            'terisi' => $terisi,
            'total'  => count($kolom),
            'persen' => (int) round($terisi / count($kolom) * 100),
        ];
    }
}
