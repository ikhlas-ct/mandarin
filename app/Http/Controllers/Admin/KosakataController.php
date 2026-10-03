<?php

namespace App\Http\Controllers\Admin;

use App\Exports\KosakataTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\KosakataRequest;
use App\Imports\KosakataImport;
use App\Models\Kategori;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KosakataController extends Controller
{
    public function index(Request $request)
    {
        $kosakatas = Kosakata::with(['kategori', 'levelHsk'])
            ->withCount('contohKalimats')
            ->cari($request->input('search'))
            ->when($request->filled('kategori_id'), function ($q) use ($request) {
                $request->kategori_id === 'kosong'
                    ? $q->whereNull('kategori_id')
                    : $q->where('kategori_id', $request->kategori_id);
            })
            ->when($request->filled('level_hsk_id'), function ($q) use ($request) {
                $request->level_hsk_id === 'kosong'
                    ? $q->whereNull('level_hsk_id')
                    : $q->where('level_hsk_id', $request->level_hsk_id);
            })
            ->when($request->input('contoh') === 'ada', fn ($q) => $q->has('contohKalimats'))
            ->when($request->input('contoh') === 'kosong', fn ($q) => $q->doesntHave('contohKalimats'))
            ->urut()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'          => Kosakata::count(),
            'dengan_contoh'  => Kosakata::has('contohKalimats')->count(),
            'tanpa_contoh'   => Kosakata::doesntHave('contohKalimats')->count(),
            'tanpa_kategori' => Kosakata::whereNull('kategori_id')->count(),
        ];

        return view('pages.kosakata.index', $this->pilihan() + compact('kosakatas', 'stats'));
    }

    public function create()
    {
        return view('pages.kosakata.create', $this->pilihan());
    }

    public function store(KosakataRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('contoh_kalimat');
        // Urutan dikosongkan -> otomatis ditaruh paling akhir.
        $data['urutan'] = $data['urutan'] ?? ((int) Kosakata::max('urutan') + 1);

        $kosakata = DB::transaction(function () use ($data, $request) {
            $kosakata = Kosakata::create($data);
            $this->simpanContoh($kosakata, $request->validated('contoh_kalimat') ?? []);

            return $kosakata;
        });

        return redirect()
            ->route('admin.kosakata.index')
            ->with('success', "Kosakata \"{$kosakata->hanzi}\" berhasil ditambahkan.");
    }

    public function show(Kosakata $kosakata)
    {
        $kosakata->load(['kategori', 'levelHsk', 'contohKalimats']);

        return view('pages.kosakata.show', compact('kosakata'));
    }

    public function edit(Kosakata $kosakata)
    {
        $kosakata->load('contohKalimats');

        return view('pages.kosakata.edit', $this->pilihan() + compact('kosakata'));
    }

    public function update(KosakataRequest $request, Kosakata $kosakata): RedirectResponse
    {
        $data = $request->safe()->except('contoh_kalimat');
        $data['urutan'] = $data['urutan'] ?? $kosakata->urutan;

        DB::transaction(function () use ($data, $request, $kosakata) {
            $kosakata->update($data);
            $this->simpanContoh($kosakata, $request->validated('contoh_kalimat') ?? []);
        });

        return redirect()
            ->route('admin.kosakata.index')
            ->with('success', "Kosakata \"{$kosakata->hanzi}\" berhasil diperbarui.");
    }

    public function destroy(Kosakata $kosakata): RedirectResponse
    {
        $hanzi = $kosakata->hanzi;

        // Contoh kalimat, progres hafalan, dan riwayat review ikut terhapus (ON DELETE CASCADE).
        $kosakata->delete();

        return redirect()
            ->route('admin.kosakata.index')
            ->with('success', "Kosakata \"{$hanzi}\" berhasil dihapus.");
    }

    /* ===================== IMPORT EXCEL ===================== */

    public function importForm()
    {
        return view('pages.kosakata.import', [
            'levels' => LevelHsk::orderBy('tingkat')->get(),
        ]);
    }

    public function importStore(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
            'mode' => ['required', 'in:perbarui,lewati'],
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $import = new KosakataImport($request->input('mode') === 'perbarui');

        try {
            // Satu transaksi: kalau ada error tak terduga, tidak ada data setengah masuk.
            DB::transaction(function () use ($import, $request) {
                Excel::import($import, $request->file('file'));
                $import->selesai();
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'File gagal diproses: ' . $e->getMessage());
        }

        if (! $import->adaIsi()) {
            return back()->with('error',
                'Tidak ada baris yang terbaca. Pastikan ada sheet bernama "Kosakata" dengan judul kolom di baris pertama.');
        }

        return redirect()
            ->route('admin.kosakata.import')
            ->with('hasil', $import->ringkasan());
    }

    public function template()
    {
        return Excel::download(new KosakataTemplateExport, 'template-kosakata.xlsx');
    }

    /* ===================== HELPER ===================== */

    private function pilihan(): array
    {
        return [
            'kategoris' => Kategori::orderBy('nama')->get(['id', 'nama']),
            'levels'    => LevelHsk::orderBy('tingkat')->get(),
        ];
    }

    /**
     * Samakan contoh kalimat di database dengan yang dikirim dari form:
     * baris ber-id diperbarui, baris tanpa id dibuat, sisanya dihapus.
     */
    private function simpanContoh(Kosakata $kosakata, array $baris): void
    {
        $dipertahankan = [];

        foreach ($baris as $b) {
            $isi = [
                'hanzi'               => $b['hanzi'],
                'pinyin'              => $b['pinyin'],
                'arti_indonesia'      => $b['arti_indonesia'],
                'catatan_tata_bahasa' => $b['catatan_tata_bahasa'] ?? null,
            ];

            // find() lewat relasi -> id milik kosakata lain tidak bisa ikut diubah.
            $contoh = ! empty($b['id']) ? $kosakata->contohKalimats()->find($b['id']) : null;

            if ($contoh) {
                $contoh->update($isi);
            } else {
                $contoh = $kosakata->contohKalimats()->create($isi);
            }

            $dipertahankan[] = $contoh->id;
        }

        $kosakata->contohKalimats()->whereNotIn('id', $dipertahankan)->delete();
    }
}
