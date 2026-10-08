<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupKosakata extends Model
{
    protected $table = 'grup_kosakatas';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function kosakatas(): BelongsToMany
    {
        return $this->belongsToMany(Kosakata::class, 'grup_kosakata_kosakata', 'grup_kosakata_id', 'kosakata_id');
    }

    /** Latihan (grup soal) yang dibuat dari grup kosakata ini lewat generator. */
    public function grupSoals(): HasMany
    {
        return $this->hasMany(GrupSoal::class, 'grup_kosakata_id');
    }
}
