<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Kosakata;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    // ===================== READ (daftar) =====================
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $kategoris = Kategori::withCount('kosakatas')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('nama', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->when($request->query('isi') === 'ada', fn ($q) => $q->has('kosakatas'))
            ->when($request->query('isi') === 'kosong', fn ($q) => $q->doesntHave('kosakatas'))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total'          => Kategori::count(),
            'terpakai'       => Kategori::has('kosakatas')->count(),
            'kosong'         => Kategori::doesntHave('kosakatas')->count(),
            'tanpa_kategori' => Kosakata::whereNull('kategori_id')->count(),
        ];

        return view('pages.kategori.index', compact('kategoris', 'stats'));
    }

    // ===================== CREATE =====================
    public function create()
    {
        return view('pages.kategori.create');
    }

    public function store(Request $request)
    {
        $kategori = Kategori::create($this->validated($request));

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil ditambahkan.");
    }

    // ===================== READ (detail) =====================
    public function show(Kategori $kategori)
    {
        $kosakatas = $kategori->kosakatas()
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10);

        return view('pages.kategori.show', compact('kategori', 'kosakatas'));
    }

    // ===================== UPDATE =====================
    public function edit(Kategori $kategori)
    {
        $kategori->loadCount('kosakatas');

        return view('pages.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($this->validated($request, $kategori));

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', "Kategori \"{$kategori->nama}\" berhasil diperbarui.");
    }

    // ===================== DELETE =====================
    public function destroy(Kategori $kategori)
    {
        // FK kosakatas.kategori_id = ON DELETE SET NULL,
        // jadi kosakata tidak ikut terhapus, hanya menjadi "tanpa kategori".
        $jumlah = $kategori->kosakatas()->count();
        $nama   = $kategori->nama;

        $kategori->delete();

        $pesan = "Kategori \"{$nama}\" berhasil dihapus.";
        if ($jumlah > 0) {
            $pesan .= " {$jumlah} kosakata sekarang tanpa kategori.";
        }

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', $pesan);
    }

    // ===================== VALIDASI =====================
    private function validated(Request $request, ?Kategori $kategori = null): array
    {
        return $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kategoris', 'nama')->ignore($kategori?->id),
            ],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.max'      => 'Nama kategori maksimal 50 karakter.',
            'nama.unique'   => 'Nama kategori sudah dipakai, gunakan nama lain.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ]);
    }
}
