<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menghubungkan latihan hasil generate dengan grup kosakata asalnya,
     * supaya halaman detail grup bisa menampilkan soal apa saja yang sudah dibuat.
     * Dihapusnya grup TIDAK menghapus latihan (nullOnDelete), karena latihan bisa
     * saja sudah dikerjakan pelajar.
     */
    public function up(): void
    {
        Schema::table('grup_soals', function (Blueprint $table) {
            $table->foreignId('grup_kosakata_id')
                ->nullable()
                ->after('level_hsk_id')
                ->constrained('grup_kosakatas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('grup_soals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('grup_kosakata_id');
        });
    }
};
