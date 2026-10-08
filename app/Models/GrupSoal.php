<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupSoal extends Model
{
    public const LATIHAN = 'latihan';
    public const UJIAN   = 'ujian';

    protected $table = 'grup_soals';

    protected $fillable = [
        'level_hsk_id',
        'grup_kosakata_id',
        'jenis',
        'judul',
        'deskripsi',
        'durasi_menit',
        'nilai_lulus',
        'maks_percobaan',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'durasi_menit'   => 'integer',
            'nilai_lulus'    => 'integer',
            'maks_percobaan' => 'integer',
            'aktif'          => 'boolean',
        ];
    }

    public function levelHsk(): BelongsTo
    {
        return $this->belongsTo(LevelHsk::class, 'level_hsk_id');
    }

    /** Grup kosakata asal latihan ini (null kalau dibuat manual). */
    public function grupKosakata(): BelongsTo
    {
        return $this->belongsTo(GrupKosakata::class, 'grup_kosakata_id');
    }

    /** Soal di dalam grup, sudah terurut. */
    public function soals(): HasMany
    {
        return $this->hasMany(Soal::class, 'grup_soal_id')->urut();
    }

    public function hasilUjians(): HasMany
    {
        return $this->hasMany(HasilUjian::class, 'grup_soal_id');
    }

    /** Hanya grup yang aktif (tampil untuk pelajar). */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }

    public function scopeLatihan(Builder $query): Builder
    {
        return $query->where('jenis', self::LATIHAN);
    }

    public function scopeUjian(Builder $query): Builder
    {
        return $query->where('jenis', self::UJIAN);
    }

    public function isUjian(): bool
    {
        return $this->jenis === self::UJIAN;
    }

    /** Jumlah seluruh poin dari soal di grup ini. */
    public function getTotalPoinAttribute(): int
    {
        return (int) $this->soals->sum('poin');
    }

    /**
     * Sisa percobaan untuk seorang pelajar.
     * null = tanpa batas. 0 = sudah tidak boleh mengerjakan lagi.
     */
    public function sisaPercobaan(int $pelajarId): ?int
    {
        if ($this->maks_percobaan === null) {
            return null;
        }

        $terpakai = $this->hasilUjians()->where('pelajar_id', $pelajarId)->count();

        return max(0, $this->maks_percobaan - $terpakai);
    }
}
