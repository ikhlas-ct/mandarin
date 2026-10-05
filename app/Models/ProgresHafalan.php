<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ProgresHafalan extends Model
{
    public const INGAT_SEPENUHNYA = 'ingat_sepenuhnya';
    public const LUPA_DAN_INGAT   = 'lupa_dan_ingat';
    public const BERIKUTNYA       = 'berikutnya';

    protected $table = 'progres_hafalans';

    protected $fillable = [
        'pelajar_id',
        'kosakata_id',
        'status',
        'terakhir_diulang',
        'jumlah_ulang',
        'benar_beruntun',
        'review_berikutnya',
    ];

    protected function casts(): array
    {
        return [
            'terakhir_diulang'  => 'datetime',
            'review_berikutnya' => 'datetime',
            'jumlah_ulang'      => 'integer',
            'benar_beruntun'    => 'integer',
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

    /** Hanya kelompok yang ikut popup review: lupa_dan_ingat & ingat_sepenuhnya. */
    public function scopeUntukPopup(Builder $query): Builder
    {
        return $query->whereIn('status', [self::LUPA_DAN_INGAT, self::INGAT_SEPENUHNYA]);
    }

    /** Kata yang belum pernah dijadwalkan atau jadwalnya sudah lewat. */
    public function scopeJatuhTempo(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereNull('review_berikutnya')
              ->orWhere('review_berikutnya', '<=', now());
        });
    }

    /**
     * Catat satu jawaban review, lalu atur jadwal berikutnya:
     * - lupa_dan_ingat            : besok (mulai 00:00)
     * - ingat_sepenuhnya + benar  : 7 hari lagi
     * - ingat_sepenuhnya + salah  : besok (mulai 00:00)
     * - berikutnya                : tidak dijadwalkan
     * Status kelompok tidak diubah otomatis.
     */
    public function catatJawaban(bool $benar, string $sumber = 'popup'): void
    {
        DB::transaction(function () use ($benar, $sumber) {
            $jadwal = match (true) {
                $this->status === self::BERIKUTNYA                    => null,
                $this->status === self::INGAT_SEPENUHNYA && $benar    => now()->addDays(7),
                default                                               => now()->addDay()->startOfDay(),
            };

            $this->update([
                'terakhir_diulang'  => now(),
                'jumlah_ulang'      => $this->jumlah_ulang + 1,
                'benar_beruntun'    => $benar ? $this->benar_beruntun + 1 : 0,
                'review_berikutnya' => $jadwal,
            ]);

            RiwayatReview::create([
                'pelajar_id'         => $this->pelajar_id,
                'kosakata_id'        => $this->kosakata_id,
                'sumber'             => $sumber,
                'status_saat_review' => $this->status,
                'benar'              => $benar,
                'direview_pada'      => now(),
            ]);
        });
    }

    /**
     * Catat satu jawaban flashcard ("Lupa" / "Ingat") sekaligus memindah status:
     * - Lupa  -> lupa_dan_ingat,   review besok (mulai 00:00)
     * - Ingat -> ingat_sepenuhnya, review 7 hari lagi
     * Riwayat mencatat status SEBELUM dipindah. Sumber memakai 'popup'
     * karena enum riwayat_reviews.sumber hanya mengenal 'popup' dan 'soal'.
     */
    public function catatKartu(bool $ingat): void
    {
        DB::transaction(function () use ($ingat) {
            $statusLama = $this->status;

            $this->update([
                'status'            => $ingat ? self::INGAT_SEPENUHNYA : self::LUPA_DAN_INGAT,
                'terakhir_diulang'  => now(),
                'jumlah_ulang'      => (int) $this->jumlah_ulang + 1,
                'benar_beruntun'    => $ingat ? (int) $this->benar_beruntun + 1 : 0,
                'review_berikutnya' => $ingat ? now()->addDays(7) : now()->addDay()->startOfDay(),
            ]);

            RiwayatReview::create([
                'pelajar_id'         => $this->pelajar_id,
                'kosakata_id'        => $this->kosakata_id,
                'sumber'             => 'popup',
                'status_saat_review' => $statusLama,
                'benar'              => $ingat,
                'direview_pada'      => now(),
            ]);
        });
    }
}
