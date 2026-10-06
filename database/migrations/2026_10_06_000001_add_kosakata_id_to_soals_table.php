<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menghubungkan setiap soal ke kosakata yang diujikan.
 * Tanpa kolom ini, hasil jawaban tidak bisa diubah menjadi status hafalan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->foreignId('kosakata_id')
                ->nullable()
                ->after('grup_soal_id')
                ->constrained('kosakatas')
                ->nullOnDelete();
        });

        $this->isiSoalLama();
    }

    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kosakata_id');
        });
    }

    /**
     * Soal yang sudah dibuat generator sebelum kolom ini ada ditebak ulang:
     * kosakata yang hanzi-nya muncul di penjelasan DAN hanzi/artinya sama
     * dengan teks jawaban yang benar. Yang tidak cocok dibiarkan null.
     */
    private function isiSoalLama(): void
    {
        $kosakatas = DB::table('kosakatas')
            ->get(['id', 'hanzi', 'arti_indonesia'])
            ->sortByDesc(fn ($k) => mb_strlen((string) $k->hanzi))
            ->values();

        DB::table('soals')
            ->whereNull('kosakata_id')
            ->orderBy('id')
            ->get(['id', 'jawaban_benar', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'penjelasan'])
            ->each(function ($soal) use ($kosakatas) {
                $teksBenar = trim((string) $soal->{'pilihan_' . strtolower($soal->jawaban_benar)});
                $penjelasan = (string) $soal->penjelasan;

                $cocok = $kosakatas->first(function ($k) use ($teksBenar, $penjelasan) {
                    $hanzi = trim((string) $k->hanzi);

                    if ($hanzi === '' || mb_strpos($penjelasan, $hanzi) === false) {
                        return false;
                    }

                    return $teksBenar === $hanzi
                        || mb_strtolower($teksBenar) === mb_strtolower(trim((string) $k->arti_indonesia));
                });

                if ($cocok) {
                    DB::table('soals')->where('id', $soal->id)->update(['kosakata_id' => $cocok->id]);
                }
            });
    }
};
