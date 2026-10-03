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
}
