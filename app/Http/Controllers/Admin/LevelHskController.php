<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kosakata;
use App\Models\LevelHsk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelHskController extends Controller
{
    // ===================== READ (daftar) =====================
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $levels = LevelHsk::withCount('kosakatas')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('nama', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%")
                      ->orWhere('tingkat', $search);
                });
            })
            ->when($request->query('isi') === 'ada', fn ($q) => $q->has('kosakatas'))
            ->when($request->query('isi') === 'kosong', fn ($q) => $q->doesntHave('kosakatas'))
            ->orderBy('tingkat')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'      => LevelHsk::count(),
            'terpakai'   => LevelHsk::has('kosakatas')->count(),
            'kosong'     => LevelHsk::doesntHave('kosakatas')->count(),
            'tanpa_level' => Kosakata::whereNull('level_hsk_id')->count(),
        ];

        return view('pages.hsk.index', compact('levels', 'stats'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        return view('pages.hsk.create');
    }

    public function store(Request $request)
    {
        $level = LevelHsk::create($this->validated($request));

        return redirect()
            ->route('admin.level-hsk.index')
            ->with('success', "Tingkat \"{$level->nama}\" berhasil ditambahkan.");
    }

    // ===================== READ (detail) =====================
    public function show(LevelHsk $levelHsk)
    {
        $kosakatas = $levelHsk->kosakatas()
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10);

        return view('pages.hsk.show', [
            'level'     => $levelHsk,
            'kosakatas' => $kosakatas,
        ]);
    }

    // ===================== UPDATE =====================
    public function edit(LevelHsk $levelHsk)
    {
        $levelHsk->loadCount('kosakatas');

        return view('pages.hsk.edit', ['level' => $levelHsk]);
    }

    public function update(Request $request, LevelHsk $levelHsk)
    {
        $levelHsk->update($this->validated($request, $levelHsk));

        return redirect()
            ->route('admin.level-hsk.index')
            ->with('success', "Tingkat \"{$levelHsk->nama}\" berhasil diperbarui.");
    }

    // ===================== DELETE =====================
    public function destroy(LevelHsk $levelHsk)
    {
        // Diasumsikan FK kosakatas.level_hsk_id = ON DELETE SET NULL,
        // jadi kosakata tidak ikut terhapus, hanya menjadi "tanpa tingkat".
        $jumlah = $levelHsk->kosakatas()->count();
        $nama   = $levelHsk->nama;

        $levelHsk->delete();

        $pesan = "Tingkat \"{$nama}\" berhasil dihapus.";
        if ($jumlah > 0) {
            $pesan .= " {$jumlah} kosakata sekarang tanpa tingkat.";
        }

        return redirect()
            ->route('admin.level-hsk.index')
            ->with('success', $pesan);
    }

    // ===================== VALIDASI =====================
    private function validated(Request $request, ?LevelHsk $level = null): array
    {
        return $request->validate([
            'tingkat' => [
                'required',
                'integer',
                'min:1',
                'max:9',
                Rule::unique('level_hsks', 'tingkat')->ignore($level?->id),
            ],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('level_hsks', 'nama')->ignore($level?->id),
            ],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ], [
            'tingkat.required' => 'Tingkat wajib diisi.',
            'tingkat.integer'  => 'Tingkat harus berupa angka.',
            'tingkat.min'      => 'Tingkat minimal 1.',
            'tingkat.max'      => 'Tingkat maksimal 9.',
            'tingkat.unique'   => 'Tingkat ini sudah ada.',
            'nama.required'    => 'Nama tingkat wajib diisi.',
            'nama.max'         => 'Nama tingkat maksimal 50 karakter.',
            'nama.unique'      => 'Nama tingkat sudah dipakai, gunakan nama lain.',
            'keterangan.max'   => 'Keterangan maksimal 1000 karakter.',
        ]);
    }
}
