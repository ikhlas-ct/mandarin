<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelajar_id')->constrained('pelajars')->cascadeOnDelete();
            $table->foreignId('grup_soal_id')->constrained('grup_soals')->cascadeOnDelete();

            $table->unsignedSmallInteger('jumlah_soal')->default(0);
            $table->unsignedSmallInteger('jumlah_benar')->default(0);
            $table->decimal('skor', 5, 2)->default(0); // 0 - 100

            $table->timestamp('mulai_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ujians');
    }
};
