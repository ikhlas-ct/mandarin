<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function kosakatas(): HasMany
    {
        return $this->hasMany(Kosakata::class, 'kategori_id');
    }
}
