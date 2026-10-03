<?php

namespace App\Http\Controllers;

use App\Models\Konten;
use App\Models\Kategori;
use App\Models\PantiAsuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KontenController extends Controller
{
    /* ─────────────────────────────────────────────────────────── */
    /* Validasi $jenis: hanya 'berita' atau 'kegiatan'            */
    /* ─────────────────────────────────────────────────────────── */
    private function validateJenis(string $jenis): void
    {
        abort_if(! in_array($jenis, ['berita', 'kegiatan']), 404);
    }

    /* ─────────────────────────────────────────────────────────── */
    /* ✅ BARU: Cek akses — berita hanya untuk admin_dinsos        */
    /* ─────────────────────────────────────────────────────────── */
    private function validateAkses(string $jenis): void
    {
        if ($jenis === 'berita' && ! Auth::user()->isAdminDinsos()) {
            abort(403, 'Hanya Admin Dinsos yang dapat mengakses halaman berita.');
        }
    }

    /* ─────────────────────────────────────────────────────────── */
    /* INDEX                                                       */
    /* ─────────────────────────────────────────────────────────── */
    public function index(Request $request, string $jenis)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $user  = Auth::user();
        $query = Konten::with(['user', 'pantiAsuhan', 'kategori'])
            ->where('jenis_konten', $jenis);

        // Admin panti hanya melihat konten panti-nya sendiri
        if ($user->isAdminPanti()) {
            $pantiId = $user->pengurus?->panti_asuhan_id;
            $query->where('panti_asuhan_id', $pantiId);
        }

        // ── Filter pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('ringkasan', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            });
        }

        // ── Filter status (untuk kegiatan)
        if ($jenis === 'kegiatan' && $status = $request->input('status')) {
            $query->where('status', $status);
        }

        // ── Filter panti (admin dinsos saja)
        if ($jenis === 'kegiatan' && $user->isAdminDinsos() && $pantiFilter = $request->input('panti_id')) {
            $query->where('panti_asuhan_id', $pantiFilter);
        }

        $konten = $query->latest('tanggal_publikasi')->paginate(10)->withQueryString();

        // Stat counts
        $stats = [
            'total' => Konten::where('jenis_konten', $jenis)
                ->when($user->isAdminPanti(), fn($q) => $q->where('panti_asuhan_id', $user->pengurus?->panti_asuhan_id))
                ->count(),
        ];

        if ($jenis === 'kegiatan') {
            $baseQ = Konten::where('jenis_konten', 'kegiatan')
                ->when($user->isAdminPanti(), fn($q) => $q->where('panti_asuhan_id', $user->pengurus?->panti_asuhan_id));

            $stats['direncanakan'] = (clone $baseQ)->where('status', 'direncanakan')->count();
            $stats['berlangsung']  = (clone $baseQ)->where('status', 'berlangsung')->count();
            $stats['selesai']      = (clone $baseQ)->where('status', 'selesai')->count();
        }

        $daftarPanti = $user->isAdminDinsos() ? PantiAsuhan::orderBy('nama_panti')->get() : collect();

        return view('pages.konten.index', compact('konten', 'jenis', 'stats', 'daftarPanti'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* CREATE                                                      */
    /* ─────────────────────────────────────────────────────────── */
    public function create(string $jenis)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $user     = Auth::user();
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $pantis   = $user->isAdminDinsos() && $jenis === 'kegiatan'
            ? PantiAsuhan::aktif()->orderBy('nama_panti')->get()
            : collect();

        $pantiPengurus = $user->isAdminPanti()
            ? $user->pengurus?->pantiAsuhan
            : null;

        return view('pages.konten.create', compact('jenis', 'kategori', 'pantis', 'pantiPengurus'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* STORE                                                       */
    /* ─────────────────────────────────────────────────────────── */
    public function store(Request $request, string $jenis)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $rules = [
            'judul'       => 'required|string|max:255|unique:konten,judul',
            'isi'         => 'required|string',
            'ringkasan'   => 'nullable|string|max:255',
            'gambar'      => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
        ];

        if ($jenis === 'kegiatan') {
            $rules = array_merge($rules, [
                'tanggal_mulai'    => 'required|date',
                'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
                'lokasi'           => 'nullable|string|max:100',
                'status'           => 'required|in:direncanakan,berlangsung,selesai,dibatalkan',
                'jumlah_peserta'   => 'nullable|integer|min:0',
                'penanggung_jawab' => 'nullable|string|max:100',
            ]);

            if (Auth::user()->isAdminDinsos()) {
                $rules['panti_asuhan_id'] = 'nullable|exists:panti_asuhan,id';
            }
        }

        $validated = $request->validate($rules, [
            'judul.required'         => 'Judul wajib diisi.',
            'judul.unique'           => 'Judul sudah digunakan, coba judul lain.',
            'isi.required'           => 'Isi konten wajib diisi.',
            'gambar.required'        => 'Gambar sampul wajib diunggah.',
            'gambar.image'           => 'File harus berupa gambar.',
            'gambar.max'             => 'Ukuran gambar maksimal 3 MB.',
            'tanggal_mulai.required' => 'Tanggal mulai kegiatan wajib diisi.',
        ]);

        // ── Simpan gambar sampul
        $gambarPath = $request->file('gambar')->store('konten/gambar', 'public');

        // ── Tentukan panti_asuhan_id
        $pantiId = null;
        if ($jenis === 'kegiatan') {
            $user = Auth::user();
            if ($user->isAdminPanti()) {
                $pantiId = $user->pengurus?->panti_asuhan_id;
            } else {
                $pantiId = $validated['panti_asuhan_id'] ?? null;
            }
        }

        Konten::create([
            'judul'             => $validated['judul'],
            'isi'               => $validated['isi'],
            'ringkasan'         => $validated['ringkasan'] ?? null,
            'id_user'           => Auth::id(),
            'slug'              => Str::slug($validated['judul']) . '-' . time(),
            'tanggal_publikasi' => now(),
            'jenis_konten'      => $jenis,
            'id_kategori'       => $validated['id_kategori'] ?? null,
            'gambar'            => $gambarPath,
            'viewer'            => 0,
            'panti_asuhan_id'   => $pantiId,
            'tanggal_mulai'     => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai'   => $validated['tanggal_selesai'] ?? null,
            'lokasi'            => $validated['lokasi'] ?? null,
            'status'            => $jenis === 'kegiatan' ? ($validated['status'] ?? 'direncanakan') : null,
            'jumlah_peserta'    => $validated['jumlah_peserta'] ?? null,
            'penanggung_jawab'  => $validated['penanggung_jawab'] ?? null,
        ]);

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' "' . $validated['judul'] . '" berhasil ditambahkan.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SHOW (public)                                               */
    /* ─────────────────────────────────────────────────────────── */
    public function show(string $jenis, string $slug)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $konten = Konten::where('jenis_konten', $jenis)
            ->where('slug', $slug)
            ->with(['user', 'pantiAsuhan', 'kategori'])
            ->firstOrFail();

        $konten->incrementViewer();

        return view('pages.konten.show', compact('konten', 'jenis'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* EDIT                                                        */
    /* ─────────────────────────────────────────────────────────── */
    public function edit(string $jenis, string $slug)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $user   = Auth::user();
        $konten = Konten::where('jenis_konten', $jenis)->where('slug', $slug)->firstOrFail();

        // Admin panti hanya boleh edit milik pantinya
        if ($user->isAdminPanti()) {
            abort_if($konten->panti_asuhan_id !== $user->pengurus?->panti_asuhan_id, 403);
        }

        $kategori = Kategori::orderBy('nama_kategori')->get();
        $pantis   = $user->isAdminDinsos() && $jenis === 'kegiatan'
            ? PantiAsuhan::aktif()->orderBy('nama_panti')->get()
            : collect();

        $pantiPengurus = $user->isAdminPanti()
            ? $user->pengurus?->pantiAsuhan
            : null;

        return view('pages.konten.edit', compact('konten', 'jenis', 'kategori', 'pantis', 'pantiPengurus'));
    }

    /* ─────────────────────────────────────────────────────────── */
    /* UPDATE                                                      */
    /* ─────────────────────────────────────────────────────────── */
    public function update(Request $request, string $jenis, int $id_konten)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $user   = Auth::user();
        $konten = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);

        if ($user->isAdminPanti()) {
            abort_if($konten->panti_asuhan_id !== $user->pengurus?->panti_asuhan_id, 403);
        }

        $rules = [
            'judul'       => 'required|string|max:255|unique:konten,judul,' . $id_konten . ',id_konten',
            'isi'         => 'required|string',
            'ringkasan'   => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'id_kategori' => 'nullable|exists:kategori,id_kategori',
        ];

        if ($jenis === 'kegiatan') {
            $rules = array_merge($rules, [
                'tanggal_mulai'    => 'required|date',
                'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
                'lokasi'           => 'nullable|string|max:100',
                'status'           => 'required|in:direncanakan,berlangsung,selesai,dibatalkan',
                'jumlah_peserta'   => 'nullable|integer|min:0',
                'penanggung_jawab' => 'nullable|string|max:100',
            ]);

            if ($user->isAdminDinsos()) {
                $rules['panti_asuhan_id'] = 'nullable|exists:panti_asuhan,id';
            }
        }

        $validated = $request->validate($rules, [
            'judul.required' => 'Judul wajib diisi.',
            'judul.unique'   => 'Judul sudah digunakan.',
            'isi.required'   => 'Isi konten wajib diisi.',
        ]);

        // ── Update gambar jika ada yang baru
        $data = collect($validated)->except('panti_asuhan_id')->toArray();
        if ($request->hasFile('gambar')) {
            if ($konten->gambar && Storage::disk('public')->exists($konten->gambar)) {
                Storage::disk('public')->delete($konten->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('konten/gambar', 'public');
        } else {
            unset($data['gambar']);
        }

        // ── Update slug jika judul berubah
        if ($validated['judul'] !== $konten->judul) {
            $data['slug'] = Str::slug($validated['judul']) . '-' . time();
        }

        // ── panti_asuhan_id
        if ($jenis === 'kegiatan') {
            if ($user->isAdminPanti()) {
                $data['panti_asuhan_id'] = $user->pengurus?->panti_asuhan_id;
            } else {
                $data['panti_asuhan_id'] = $request->input('panti_asuhan_id');
            }
        }

        $konten->update($data);

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' berhasil diperbarui.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* DESTROY                                                     */
    /* ─────────────────────────────────────────────────────────── */
    public function destroy(string $jenis, int $id_konten)
    {
        $this->validateJenis($jenis);
        $this->validateAkses($jenis); // ← tambah ini

        $user   = Auth::user();
        $konten = Konten::where('jenis_konten', $jenis)->findOrFail($id_konten);

        if ($user->isAdminPanti()) {
            abort_if($konten->panti_asuhan_id !== $user->pengurus?->panti_asuhan_id, 403);
        }

        if ($konten->gambar && Storage::disk('public')->exists($konten->gambar)) {
            Storage::disk('public')->delete($konten->gambar);
        }

        $judul = $konten->judul;
        $konten->delete();

        return redirect()->route('konten.index', $jenis)
            ->with('success', ucfirst($jenis) . ' "' . $judul . '" berhasil dihapus.');
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SUMMERNOTE – Upload gambar inline                          */
    /* ─────────────────────────────────────────────────────────── */
    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:3072']);

        $path = $request->file('image')->store('konten/isi', 'public');
        $url  = asset('storage/' . $path);

        return response()->json(['url' => $url]);
    }

    /* ─────────────────────────────────────────────────────────── */
    /* SUMMERNOTE – Hapus gambar inline                           */
    /* ─────────────────────────────────────────────────────────── */
    public function deleteImage(Request $request)
    {
        $src  = $request->input('src', '');
        $path = str_replace(asset('storage') . '/', '', $src);

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return response()->json(['success' => true]);
    }
}
