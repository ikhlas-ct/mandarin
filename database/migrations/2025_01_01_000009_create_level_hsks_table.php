<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('level_hsks', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('tingkat')->unique(); // 1, 2, 3, 4, 5
            $table->string('nama', 20);                       // HSK 1, HSK 2, ...
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('level_hsks');
    }
};
