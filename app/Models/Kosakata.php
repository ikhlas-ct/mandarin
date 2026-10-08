<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kosakata extends Model
{
    // Ditulis eksplisit karena pluralisasi otomatis Laravel berbasis bahasa Inggris.
    protected $table = 'kosakatas';

    protected $fillable = [
        'kategori_id',
        'level_hsk_id',
        'hanzi',
        'pinyin',
        'baca_indonesia',
        'english',
        'arti_indonesia',
        'kegunaan',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function levelHsk(): BelongsTo
    {
        return $this->belongsTo(LevelHsk::class, 'level_hsk_id');
    }

    public function contohKalimats(): HasMany
    {
        return $this->hasMany(ContohKalimat::class, 'kosakata_id');
    }

    public function progresHafalans(): HasMany
    {
        return $this->hasMany(ProgresHafalan::class, 'kosakata_id');
    }

    public function riwayatReviews(): HasMany
    {
        return $this->hasMany(RiwayatReview::class, 'kosakata_id');
    }

    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Urut berdasarkan level HSK dulu (tingkat kecil -> besar, tanpa level di akhir),
     * lalu kolom urutan, lalu id.
     */
    public function scopeUrutLevel(Builder $query): Builder
    {
        return $query
            ->orderByRaw('coalesce((select tingkat from level_hsks where level_hsks.id = kosakatas.level_hsk_id), 255)')
            ->orderBy('kosakatas.urutan')
            ->orderBy('kosakatas.id');
    }

    /** Cari berdasarkan hanzi, pinyin, arti Indonesia, atau English. */
    public function scopeCari(Builder $query, ?string $kata): Builder
    {
        $kata = trim((string) $kata);

        if ($kata === '') {
            return $query;
        }

        return $query->where(function (Builder $w) use ($kata) {
            $w->where('hanzi', 'like', "%{$kata}%")
              ->orWhere('pinyin', 'like', "%{$kata}%")
              ->orWhere('arti_indonesia', 'like', "%{$kata}%")
              ->orWhere('english', 'like', "%{$kata}%");
        });
    }
}
