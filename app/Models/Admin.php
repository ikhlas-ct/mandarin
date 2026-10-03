<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'no_telp',
        'alamat',
        'foto',
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
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama ?? 'A')
                 . '&background=e9ecef&color=6c757d&size=120';
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return asset('storage/' . $this->foto);
    }
}
