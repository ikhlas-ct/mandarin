<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grup_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_hsk_id')->nullable()->constrained('level_hsks')->nullOnDelete();

            $table->enum('jenis', ['latihan', 'ujian'])->default('latihan');

            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('durasi_menit')->nullable();   // kosong = tanpa batas waktu
            $table->unsignedTinyInteger('nilai_lulus')->nullable();    // khusus ujian (0 - 100)
            $table->unsignedTinyInteger('maks_percobaan')->nullable(); // kosong = tanpa batas
            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->index(['jenis', 'aktif']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grup_soals');
    }
};
