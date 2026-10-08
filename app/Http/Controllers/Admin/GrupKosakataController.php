<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrupKosakata;
use App\Models\GrupSoal;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use App\Services\GeneratorSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class GrupKosakataController extends Controller
{
    /** Label tampilan untuk tipe soal yang bisa dibuat generator. */
    public const LABEL_TIPE = [
        'hanzi_arti' => 'Hanzi → arti',
        'arti_hanzi' => 'Arti → hanzi',
        'isian'      => 'Isian kalimat',
        'listening'  => 'Listening',
    ];

    /* ------------------------------------------------------------------ */
    /*  CRUD                                                               */
    /* ------------------------------------------------------------------ */

    public function index(Request $request)
    {
        $cari = trim((string) $request->input('search'));

        $grups = GrupKosakata::query()
            ->withCount(['kosakatas', 'grupSoals'])
            // Hanya hanzi yang dibutuhkan untuk pratinjau di kartu.
            ->with(['kosakatas' => fn ($q) => $q->select('kosakatas.id', 'kosakatas.hanzi')])
            ->when($cari !== '', function ($q) use ($cari) {
                $q->where(function ($w) use ($cari) {
                    $w->where('nama', 'like', "%{$cari}%")
                      ->orWhere('keterangan', 'like', "%{$cari}%")
                      ->orWhereHas('kosakatas', fn ($k) => $k->cari($cari));
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $ringkasan = [
            'grup'    => GrupKosakata::count(),
            'latihan' => GrupSoal::whereNotNull('grup_kosakata_id')->count(),
            'soal'    => DB::table('soals')
                ->join('grup_soals', 'grup_soals.id', '=', 'soals.grup_soal_id')
                ->whereNotNull('grup_soals.grup_kosakata_id')
                ->count(),
        ];

        return view('pages.grup-kosakata.index', compact('grups', 'ringkasan', 'cari'));
    }

    public function create()
    {
        return view('pages.grup-kosakata.create', [
            'kosakatas' => $this->daftarKosakata(),
            'levels'    => LevelHsk::orderBy('tingkat')->get(['id', 'nama']),
            'terpilih'  => array_map('intval', old('kosakata_ids', [])),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $grup = DB::transaction(function () use ($data) {
            $grup = GrupKosakata::create([
                'nama'       => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);
            $grup->kosakatas()->sync($data['kosakata_ids']);

            return $grup;
        });

        return redirect()->route('admin.grup-kosakata.show', $grup)
            ->with('success', "Grup \"{$grup->nama}\" dibuat dengan " . count($data['kosakata_ids']) . ' kata. Sekarang Anda bisa membuat soal darinya.');
    }

    public function show(GrupKosakata $grup)
    {
        $grup->load([
            'kosakatas' => fn ($q) => $q->with('levelHsk')
                ->orderBy('kosakatas.urutan')
                ->orderBy('kosakatas.id'),
        ]);

        $latihans = $grup->grupSoals()
            ->withCount('soals')
            ->with(['soals.kosakata', 'levelHsk'])
            ->latest()
            ->get();

        // Berapa soal yang menguji tiap kata (lintas semua latihan grup ini).
        $soalPerKata = $latihans->flatMap->soals
            ->whereNotNull('kosakata_id')
            ->groupBy('kosakata_id')
            ->map->count();

        // Latihan yang belum tertaut ke grup mana pun (mis. dibuat sebelum kolom grup_kosakata_id ada).
        $belumTertaut = GrupSoal::whereNull('grup_kosakata_id')
            ->withCount('soals')
            ->latest()
            ->get();

        $ringkasan = [
            'kata'          => $grup->kosakatas->count(),
            'latihan'       => $latihans->count(),
            'latihan_aktif' => $latihans->where('aktif', true)->count(),
            'soal'          => $latihans->sum('soals_count'),
        ];

        return view('pages.grup-kosakata.show', [
            'grup'        => $grup,
            'latihans'    => $latihans,
            'soalPerKata' => $soalPerKata,
            'belumTertaut' => $belumTertaut,
            'ringkasan'   => $ringkasan,
            'labelTipe'   => self::LABEL_TIPE,
        ]);
    }

    public function edit(GrupKosakata $grup)
    {
        return view('pages.grup-kosakata.edit', [
            'grup'      => $grup,
            'kosakatas' => $this->daftarKosakata(),
            'levels'    => LevelHsk::orderBy('tingkat')->get(['id', 'nama']),
            'terpilih'  => array_map('intval', old(
                'kosakata_ids',
                $grup->kosakatas()->pluck('kosakatas.id')->all()
            )),
        ]);
    }

    public function update(Request $request, GrupKosakata $grup)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($grup, $data) {
            $grup->update([
                'nama'       => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);
            $grup->kosakatas()->sync($data['kosakata_ids']);
        });

        return redirect()->route('admin.grup-kosakata.show', $grup)
            ->with('success', 'Grup diperbarui. Soal yang sudah dibuat sebelumnya tidak ikut berubah.');
    }

    public function destroy(GrupKosakata $grup)
    {
        $nama = $grup->nama;
        $grup->delete(); // latihan hasil generate tetap ada (grup_kosakata_id jadi null)

        return redirect()->route('admin.grup-kosakata.index')
            ->with('success', "Grup \"{$nama}\" dihapus. Latihan yang pernah dibuat darinya tetap tersimpan.");
    }

    /* ------------------------------------------------------------------ */
    /*  Generator & pengelolaan latihan hasil generate                     */
    /* ------------------------------------------------------------------ */

    public function generate(Request $request, GrupKosakata $grup, GeneratorSoal $generator)
    {
        $data = $request->validate([
            'tipe'   => ['required', 'array', 'min:1'],
            'tipe.*' => ['in:' . implode(',', GeneratorSoal::TIPE)],
            'jumlah' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'tipe.required' => 'Pilih minimal satu tipe soal.',
            'tipe.min'      => 'Pilih minimal satu tipe soal.',
        ]);

        $kembali = redirect()->route('admin.grup-kosakata.show', $grup);

        try {
            $grupSoal = $generator->buat($grup, $data['tipe'], (int) $data['jumlah']);
        } catch (InvalidArgumentException $e) {
            return $kembali->withInput()->with('error', $e->getMessage());
        }

        // Catat asal latihan supaya muncul di halaman detail grup.
        if ((int) $grupSoal->grup_kosakata_id !== (int) $grup->id) {
            $grupSoal->update(['grup_kosakata_id' => $grup->id]);
        }

        return $kembali->with('success',
            "Dibuat latihan \"{$grupSoal->judul}\" berisi {$grupSoal->soals_count} soal (status: belum aktif, silakan dicek dulu)."
        );
    }

    /** Aktifkan / nonaktifkan latihan supaya tampil atau tersembunyi bagi pelajar. */
    public function toggleLatihan(GrupKosakata $grup, GrupSoal $grupSoal)
    {
        abort_unless((int) $grupSoal->grup_kosakata_id === (int) $grup->id, 404);

        $grupSoal->update(['aktif' => ! $grupSoal->aktif]);

        return back()->with('success', $grupSoal->aktif
            ? "Latihan \"{$grupSoal->judul}\" diaktifkan dan sekarang tampil untuk pelajar."
            : "Latihan \"{$grupSoal->judul}\" dinonaktifkan."
        );
    }

    /** Tautkan latihan lama (belum punya grup) ke grup ini supaya bisa dikelola & diedit. */
    public function tautkanLatihan(GrupKosakata $grup, GrupSoal $grupSoal)
    {
        abort_if($grupSoal->grup_kosakata_id !== null, 404);

        $grupSoal->update(['grup_kosakata_id' => $grup->id]);

        return back()->with('success',
            "Latihan \"{$grupSoal->judul}\" ditautkan ke grup ini. Sekarang bisa diedit lewat tombol Edit."
        );
    }

    /** Form edit pengaturan latihan (tabel grup_soals). */
    public function editLatihan(GrupKosakata $grup, GrupSoal $grupSoal)
    {
        abort_unless((int) $grupSoal->grup_kosakata_id === (int) $grup->id, 404);

        return view('pages.grup-kosakata.edit-latihan', [
            'grup'             => $grup,
            'latihan'          => $grupSoal->loadCount('soals'),
            'levels'           => LevelHsk::orderBy('tingkat')->get(['id', 'nama']),
            'grups'            => GrupKosakata::orderBy('nama')->get(['id', 'nama']),
            'jumlahPengerjaan' => $grupSoal->hasilUjians()->count(),
        ]);
    }

    public function updateLatihan(Request $request, GrupKosakata $grup, GrupSoal $grupSoal)
    {
        abort_unless((int) $grupSoal->grup_kosakata_id === (int) $grup->id, 404);

        $data = $request->validate([
            'judul'            => ['required', 'string', 'max:150'],
            'deskripsi'        => ['nullable', 'string', 'max:2000'],
            'jenis'            => ['required', Rule::in([GrupSoal::LATIHAN, GrupSoal::UJIAN])],
            'level_hsk_id'     => ['nullable', 'integer', 'exists:level_hsks,id'],
            'grup_kosakata_id' => ['required', 'integer', 'exists:grup_kosakatas,id'],
            'durasi_menit'     => ['nullable', 'integer', 'min:1', 'max:65535'],
            'nilai_lulus'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'maks_percobaan'   => ['nullable', 'integer', 'min:1', 'max:255'],
        ], [
            'judul.required'            => 'Judul wajib diisi.',
            'jenis.in'                  => 'Jenis harus latihan atau ujian.',
            'grup_kosakata_id.required' => 'Pilih grup kosakata asal.',
            'durasi_menit.min'          => 'Durasi minimal 1 menit; kosongkan jika tanpa batas waktu.',
            'nilai_lulus.max'           => 'Nilai lulus maksimal 100.',
            'maks_percobaan.min'        => 'Maks percobaan minimal 1; kosongkan jika tanpa batas.',
        ]);

        $data['aktif'] = $request->boolean('aktif');

        $grupSoal->update($data);

        // Kalau latihan dipindah ke grup lain, kembali ke detail grup yang baru.
        return redirect()->route('admin.grup-kosakata.show', $data['grup_kosakata_id'])
            ->with('success', "Pengaturan latihan \"{$grupSoal->judul}\" diperbarui.");
    }

    /** Hapus satu latihan beserta soalnya, kecuali sudah pernah dikerjakan pelajar. */
    public function destroyLatihan(GrupKosakata $grup, GrupSoal $grupSoal)
    {
        abort_unless((int) $grupSoal->grup_kosakata_id === (int) $grup->id, 404);

        if ($grupSoal->hasilUjians()->exists()) {
            return back()->with('error',
                'Latihan ini sudah pernah dikerjakan pelajar sehingga tidak bisa dihapus. Nonaktifkan saja agar tidak tampil lagi.'
            );
        }

        $judul = $grupSoal->judul;
        $grupSoal->delete();

        return back()->with('success', "Latihan \"{$judul}\" dihapus.");
    }

    /* ------------------------------------------------------------------ */
    /*  Helper                                                             */
    /* ------------------------------------------------------------------ */

    private function validasi(Request $request): array
    {
        return $request->validate([
            'nama'           => ['required', 'string', 'max:150'],
            'keterangan'     => ['nullable', 'string', 'max:1000'],
            'kosakata_ids'   => ['required', 'array', 'min:2'],
            'kosakata_ids.*' => ['integer', 'distinct', 'exists:kosakatas,id'],
        ], [
            'nama.required'         => 'Nama grup wajib diisi.',
            'kosakata_ids.required' => 'Pilih minimal 2 kata untuk satu grup.',
            'kosakata_ids.min'      => 'Pilih minimal 2 kata untuk satu grup.',
        ]);
    }

    /** Semua kosakata (kolom seperlunya) untuk pemilih kata; disaring di sisi browser. */
    private function daftarKosakata()
    {
        return Kosakata::with('levelHsk:id,nama')
            ->urut()
            ->get(['id', 'level_hsk_id', 'hanzi', 'pinyin', 'arti_indonesia']);
    }
}
