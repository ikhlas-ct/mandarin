<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paragraf extends Model
{
    protected $table = 'paragrafs';

    protected $fillable = [
        'judul',
        'hanzi',
        'pinyin',
        'arti_indonesia',
        'penjelasan_tata_bahasa',
        'audio',
    ];

    /** URL audio paragraf (relatif, supaya tidak rusak kalau domain berubah), atau null kalau belum ada. */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio ? '/storage/' . ltrim($this->audio, '/') : null;
    }
}
