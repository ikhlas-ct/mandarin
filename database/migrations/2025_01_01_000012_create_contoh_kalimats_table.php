<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contoh_kalimats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kosakata_id')->constrained('kosakatas')->cascadeOnDelete();
            $table->string('hanzi', 255);
            $table->string('pinyin', 255);
            $table->string('arti_indonesia', 255);
            $table->string('catatan_tata_bahasa', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contoh_kalimats');
    }
};
