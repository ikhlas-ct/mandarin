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
    ];
}
