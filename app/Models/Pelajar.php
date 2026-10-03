<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelajar extends Model
{
    protected $table = 'pelajars';

    protected $fillable = [
        'user_id',
        'nama',
        'no_telp',
        'alamat',
        'foto',
        'status',
        'keterangan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** URL foto profil, dengan avatar inisial sebagai cadangan. */
    public function getFotoUrlAttribute(): string
    {
        if (! $this->foto) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama ?? 'P')
                 . '&background=e9ecef&color=6c757d&size=120';
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return asset('storage/' . $this->foto);
    }

    public function progresHafalans(): HasMany
    {
        return $this->hasMany(ProgresHafalan::class, 'pelajar_id');
    }

    public function riwayatReviews(): HasMany
    {
        return $this->hasMany(RiwayatReview::class, 'pelajar_id');
    }

    /** Semua kosakata beserta status hafalannya (lewat tabel progres_hafalans). */
    public function kosakatas(): BelongsToMany
    {
        return $this->belongsToMany(Kosakata::class, 'progres_hafalans', 'pelajar_id', 'kosakata_id')
            ->withPivot(['status', 'terakhir_diulang', 'jumlah_ulang', 'benar_beruntun', 'review_berikutnya'])
            ->withTimestamps();
    }

    public function hasilUjians(): HasMany
    {
        return $this->hasMany(HasilUjian::class, 'pelajar_id');
    }
}
