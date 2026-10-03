<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatReview extends Model
{
    protected $table = 'riwayat_reviews';

    // Tabel ini tidak punya created_at/updated_at; waktunya ada di direview_pada.
    public $timestamps = false;

    protected $fillable = [
        'pelajar_id',
        'kosakata_id',
        'sumber',
        'status_saat_review',
        'benar',
        'direview_pada',
    ];

    protected function casts(): array
    {
        return [
            'benar'         => 'boolean',
            'direview_pada' => 'datetime',
        ];
    }

    public function pelajar(): BelongsTo
    {
        return $this->belongsTo(Pelajar::class, 'pelajar_id');
    }

    public function kosakata(): BelongsTo
    {
        return $this->belongsTo(Kosakata::class, 'kosakata_id');
    }
}
