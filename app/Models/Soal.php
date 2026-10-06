<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soal extends Model
{
    public const LISTENING = 'listening';
    public const READING   = 'reading';

    // Ditulis eksplisit karena pluralisasi otomatis Laravel berbasis bahasa Inggris.
    protected $table = 'soals';

    protected $fillable = [
        'grup_soal_id',
        'kosakata_id',
        'bagian',
        'urutan',
        'poin',
        'paragraf',
        'audio',
        'pertanyaan',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'jawaban_benar',
        'penjelasan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
            'poin'   => 'integer',
        ];
    }

    public function grupSoal(): BelongsTo
    {
        return $this->belongsTo(GrupSoal::class, 'grup_soal_id');
    }

    /** Kosakata yang diujikan soal ini (null untuk soal manual yang tidak terkait satu kata). */
    public function kosakata(): BelongsTo
    {
        return $this->belongsTo(Kosakata::class, 'kosakata_id');
    }

    public function jawabanPelajars(): HasMany
    {
        return $this->hasMany(JawabanPelajar::class, 'soal_id');
    }

    public function scopeUrut(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /** Filter berdasarkan bagian: Soal::LISTENING atau Soal::READING. */
    public function scopeBagian(Builder $query, string $bagian): Builder
    {
        return $query->where('bagian', $bagian);
    }

    /** URL file audio mp3, null kalau belum ada. */
    public function getAudioUrlAttribute(): ?string
    {
        return $this->audio
            ? asset('storage/' . $this->audio)
            : null;
    }

    /**
     * Teks yang diucapkan TTS browser untuk soal listening (dipakai kalau belum ada file audio).
     * Diambil dari hanzi kosakata yang diujikan; null kalau bukan listening / tidak terkait kata.
     */
    public function getTeksSuaraAttribute(): ?string
    {
        if ($this->bagian !== self::LISTENING) {
            return null;
        }

        return $this->kosakata?->hanzi;
    }

    /** Pilihan jawaban dalam bentuk ['A' => ..., 'B' => ..., 'C' => ..., 'D' => ...]. */
    public function getPilihanAttribute(): array
    {
        return [
            'A' => $this->pilihan_a,
            'B' => $this->pilihan_b,
            'C' => $this->pilihan_c,
            'D' => $this->pilihan_d,
        ];
    }

    /** Cek apakah jawaban (A/B/C/D) sama dengan kunci. */
    public function cekJawaban(?string $jawaban): bool
    {
        return $jawaban !== null && strtoupper($jawaban) === $this->jawaban_benar;
    }
}
