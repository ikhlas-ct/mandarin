<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContohKalimat extends Model
{
    protected $table = 'contoh_kalimats';

    protected $fillable = [
        'kosakata_id',
        'hanzi',
        'pinyin',
        'arti_indonesia',
        'catatan_tata_bahasa',
    ];

    public function kosakata(): BelongsTo
    {
        return $this->belongsTo(Kosakata::class, 'kosakata_id');
    }
}
