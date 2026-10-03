<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelHsk extends Model
{
    protected $table = 'level_hsks';

    protected $fillable = [
        'tingkat',
        'nama',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tingkat' => 'integer',
        ];
    }

    public function kosakatas(): HasMany
    {
        return $this->hasMany(Kosakata::class, 'level_hsk_id');
    }

    public function grupSoals(): HasMany
    {
        return $this->hasMany(GrupSoal::class, 'level_hsk_id');
    }
}
