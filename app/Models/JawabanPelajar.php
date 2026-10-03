<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanPelajar extends Model
{
    protected $table = 'jawaban_pelajars';

    protected $fillable = [
        'hasil_ujian_id',
        'soal_id',
        'jawaban',
        'benar',
        'poin',
    ];

    protected function casts(): array
    {
        return [
            'benar' => 'boolean',
            'poin'  => 'integer',
        ];
    }

    public function hasilUjian(): BelongsTo
    {
        return $this->belongsTo(HasilUjian::class, 'hasil_ujian_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
