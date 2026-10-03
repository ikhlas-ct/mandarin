<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paragrafs', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100);
            $table->text('hanzi');
            $table->text('pinyin');
            $table->text('arti_indonesia');
            $table->longText('penjelasan_tata_bahasa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paragrafs');
    }
};
