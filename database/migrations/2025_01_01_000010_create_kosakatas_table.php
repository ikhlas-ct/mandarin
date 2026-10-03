<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kosakatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->nullOnDelete();
            $table->foreignId('level_hsk_id')->nullable()->constrained('level_hsks')->nullOnDelete();
            $table->string('hanzi', 20)->unique();                // hanzi tradisional
            $table->string('pinyin', 60);
            $table->string('baca_indonesia', 60)->nullable();
            $table->string('english', 150)->nullable();
            $table->string('arti_indonesia', 150);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kosakatas');
    }
};
