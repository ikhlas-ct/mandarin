<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_soal_id')->constrained('grup_soals')->cascadeOnDelete();

            $table->enum('bagian', ['listening', 'reading']);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->unsignedTinyInteger('poin')->default(1);

            $table->longText('paragraf')->nullable();   // reading (isi dari Summernote)
            $table->string('audio')->nullable();        // listening (path mp3)

            $table->text('pertanyaan');
            $table->string('pilihan_a');
            $table->string('pilihan_b');
            $table->string('pilihan_c');
            $table->string('pilihan_d');
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D']);

            $table->longText('penjelasan')->nullable();

            $table->timestamps();

            // Mempercepat pengambilan soal per grup menurut urutan
            $table->index(['grup_soal_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
