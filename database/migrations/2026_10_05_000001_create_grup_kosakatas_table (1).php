<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grup_kosakatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('grup_kosakata_kosakata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grup_kosakata_id')->constrained('grup_kosakatas')->cascadeOnDelete();
            $table->foreignId('kosakata_id')->constrained('kosakatas')->cascadeOnDelete();
            $table->unique(['grup_kosakata_id', 'kosakata_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grup_kosakata_kosakata');
        Schema::dropIfExists('grup_kosakatas');
    }
};
