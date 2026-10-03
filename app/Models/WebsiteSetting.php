<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        'nama',
        'slogan',
        'alamat',
        'email',
        'nomor_telepon',
        'logo',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_youtube',
        'title_pengantar',
        'paragraf_pengantar',
        'gambar_pengantar',
        'about_us',
    ];

    /**
     * Ambil satu-satunya baris pengaturan.
     * Kalau belum ada, kembalikan instance kosong supaya layout/sidebar
     * tidak error saat memanggil $settings->nama atau $settings->logo_url.
     */
    public static function current(): self
    {
        return static::first() ?? new static;
    }

    /**
     * Alias untuk current(). Dipakai oleh DashboardController
     * (WebsiteSetting::getSetting()), jadi jangan dihapus.
     */
    public static function getSetting(): self
    {
        return static::current();
    }

    /** URL logo. Fallback ke logo bawaan template kalau belum diunggah. */
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : asset('assets/img/kaiadmin/logo_light.svg'); // ganti sesuai logo default kamu
    }

    /** URL gambar pengantar, null kalau belum ada. */
    public function getGambarPengantarUrlAttribute(): ?string
    {
        return $this->gambar_pengantar
            ? asset('storage/' . $this->gambar_pengantar)
            : null;
    }
}
