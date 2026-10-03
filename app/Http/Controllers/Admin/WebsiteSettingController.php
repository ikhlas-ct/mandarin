<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    /** Kolom file yang disimpan di storage/app/public/website */
    private const FILE_FIELDS = ['logo', 'gambar_pengantar'];

    // Tampilkan form edit
    public function index()
    {
        // Kalau tabel masih kosong, baris pertama dibuat otomatis (semua kolom null)
        // sehingga halaman ini cukup berupa form update.
        $pengaturan = WebsiteSetting::firstOrCreate([]);

        return view('pages.setting.index', compact('pengaturan'));
    }

    // Simpan perubahan
    public function update(Request $request)
    {
        $pengaturan = WebsiteSetting::firstOrCreate([]);

        $validated = $request->validate([
            'nama'               => ['nullable', 'string', 'max:255'],
            'slogan'             => ['nullable', 'string', 'max:255'],
            'alamat'             => ['nullable', 'string'],
            'email'              => ['nullable', 'email', 'max:255'],
            'nomor_telepon'      => ['nullable', 'string', 'max:20'],
            'logo'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'social_facebook'    => ['nullable', 'url', 'max:255'],
            'social_instagram'   => ['nullable', 'url', 'max:255'],
            'social_twitter'     => ['nullable', 'url', 'max:255'],
            'social_youtube'     => ['nullable', 'url', 'max:255'],
            'title_pengantar'    => ['nullable', 'string', 'max:255'],
            'paragraf_pengantar' => ['nullable', 'string'],
            'gambar_pengantar'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_us'           => ['nullable', 'string'],
        ], [
            'email.email'          => 'Format email tidak valid.',
            'url'                  => 'Format :attribute harus berupa URL lengkap (diawali https://).',
            'logo.image'           => 'Logo harus berupa gambar.',
            'logo.max'             => 'Ukuran logo maksimal 2 MB.',
            'gambar_pengantar.max' => 'Ukuran gambar pengantar maksimal 4 MB.',
        ]);

        // File diproses terpisah dari field teks
        $data = collect($validated)->except(self::FILE_FIELDS)->all();

        foreach (self::FILE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                // hapus file lama supaya storage tidak menumpuk
                if ($pengaturan->{$field}) {
                    Storage::disk('public')->delete($pengaturan->{$field});
                }
                $data[$field] = $request->file($field)->store('website', 'public');
            }
        }

        $pengaturan->update($data);

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
