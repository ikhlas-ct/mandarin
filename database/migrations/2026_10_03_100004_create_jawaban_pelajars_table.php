<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_pelajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_ujian_id')->constrained('hasil_ujians')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soals')->cascadeOnDelete();

            $table->enum('jawaban', ['A', 'B', 'C', 'D'])->nullable(); // null = tidak dijawab
            $table->boolean('benar')->default(false);
            $table->unsignedTinyInteger('poin')->default(0);

            $table->timestamps();

            $table->unique(['hasil_ujian_id', 'soal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_pelajars');
    }
};
