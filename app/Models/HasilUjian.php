<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasilUjian extends Model
{
    // Ditulis eksplisit karena pluralisasi otomatis Laravel berbasis bahasa Inggris.
    protected $table = 'hasil_ujians';

    protected $fillable = [
        'pelajar_id',
        'grup_soal_id',
        'jumlah_soal',
        'jumlah_benar',
        'skor',
        'mulai_pada',
        'selesai_pada',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_soal'  => 'integer',
            'jumlah_benar' => 'integer',
            'skor'         => 'decimal:2',
            'mulai_pada'   => 'datetime',
            'selesai_pada' => 'datetime',
        ];
    }

    public function pelajar(): BelongsTo
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id');
    }

    public function grupSoal(): BelongsTo
    {
        return $this->belongsTo(GrupSoal::class, 'grup_soal_id');
    }

    public function jawabanPelajars(): HasMany
    {
        return $this->hasMany(JawabanPelajar::class, 'hasil_ujian_id');
    }

    /** Lulus atau tidak. null kalau grupnya tidak punya nilai lulus (misalnya latihan). */
    public function getLulusAttribute(): ?bool
    {
        $batas = $this->grupSoal?->nilai_lulus;

        return $batas === null ? null : (float) $this->skor >= $batas;
    }
}
