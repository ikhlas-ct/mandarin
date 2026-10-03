<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelajar_id')->constrained('pelajars')->cascadeOnDelete();
            $table->foreignId('kosakata_id')->constrained('kosakatas')->cascadeOnDelete();
            $table->enum('sumber', ['popup', 'soal']);
            $table->enum('status_saat_review', ['ingat_sepenuhnya', 'lupa_dan_ingat', 'berikutnya']);
            $table->boolean('benar');
            $table->timestamp('direview_pada')->useCurrent();

            $table->index(['pelajar_id', 'kosakata_id', 'direview_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_reviews');
    }
};
