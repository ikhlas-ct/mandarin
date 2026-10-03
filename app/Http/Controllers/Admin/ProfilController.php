<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

/**
 * Profil admin yang sedang login (tabel admins, satu baris per user).
 * C : store    (isi profil pertama kali)
 * R : index    (tampil profil)
 * U : update   (ubah profil + ganti foto)
 * + : password (ganti password akun)
 */
class ProfilController extends Controller
{
    /** Kolom yang dihitung untuk persentase kelengkapan profil. */
    private const FIELD_KELENGKAPAN = ['nama', 'jabatan', 'no_telp', 'alamat', 'foto', 'keterangan'];

    // ────────────────────────────── READ ──────────────────────────────
    public function index()
    {
        $user        = Auth::user();
        $admin       = $user->admin ?? new Admin();
        $kelengkapan = self::hitungKelengkapan($admin->exists ? $admin : null);

        return view('pages.admin.profil', compact('admin', 'user', 'kelengkapan'));
    }

    // ────────────────────────────── CREATE ────────────────────────────
    public function store(Request $request)
    {
        // Satu user admin hanya boleh punya satu profil.
        if (Auth::user()->admin) {
            return redirect()->route('admin.profil')
                ->with('error', 'Profil sudah ada, silakan gunakan menu Edit.');
        }

        $admin          = new Admin();
        $admin->user_id = Auth::id();
        $this->simpan($admin, $request);

        return redirect()->route('admin.profil')
            ->with('success', 'Profil berhasil disimpan.');
    }

    // ────────────────────────────── UPDATE ────────────────────────────
    public function update(Request $request)
    {
        $admin = Auth::user()->admin;

        if (! $admin) {
            return redirect()->route('admin.profil')
                ->with('error', 'Profil belum dibuat, silakan isi terlebih dahulu.')
                ->with('tab', 'edit');
        }

        $this->simpan($admin, $request);

        return redirect()->route('admin.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    // ────────────────────────────── PASSWORD ──────────────────────────
    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        // Cast 'hashed' di model User otomatis meng-hash password.
        Auth::user()->update(['password' => $request->password]);

        return redirect()->route('admin.profil')
            ->with('success', 'Password berhasil diubah.')
            ->with('tab', 'password');
    }

    // ────────────────────────────── HELPER ────────────────────────────
    private function simpan(Admin $admin, Request $request): void
    {
        $data = $request->validate([
            'nama'       => ['required', 'string', 'max:50'],
            'jabatan'    => ['nullable', 'string', 'max:50'],
            'no_telp'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'alamat'     => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max'      => 'Nama maksimal 50 karakter.',
            'no_telp.regex' => 'No. telepon hanya boleh berisi angka, spasi, +, -, ( ).',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.max'      => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Foto dikelola terpisah agar file lama ikut terhapus saat diganti.
        unset($data['foto']);

        if ($request->hasFile('foto')) {
            if ($admin->foto && ! str_starts_with($admin->foto, 'http')) {
                Storage::disk('public')->delete($admin->foto);
            }
            $data['foto'] = $request->file('foto')->store('admin', 'public');
        }

        $admin->fill($data)->save();
    }

    /** @return array{terisi:int,total:int,persen:int,kosong:array<int,string>} */
    public static function hitungKelengkapan(?Admin $admin): array
    {
        $total  = count(self::FIELD_KELENGKAPAN);
        $kosong = [];

        foreach (self::FIELD_KELENGKAPAN as $field) {
            if (! $admin || blank($admin->{$field})) {
                $kosong[] = $field;
            }
        }

        $terisi = $total - count($kosong);

        return [
            'terisi' => $terisi,
            'total'  => $total,
            'persen' => (int) round($terisi / $total * 100),
            'kosong' => $kosong,
        ];
    }
}
