<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progres_hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelajar_id')->constrained('pelajars')->cascadeOnDelete();
            $table->foreignId('kosakata_id')->constrained('kosakatas')->cascadeOnDelete();
            $table->enum('status', ['ingat_sepenuhnya', 'lupa_dan_ingat', 'berikutnya'])
                  ->default('berikutnya');
            $table->timestamp('terakhir_diulang')->nullable();
            $table->unsignedInteger('jumlah_ulang')->default(0);
            $table->unsignedInteger('benar_beruntun')->default(0);
            $table->timestamp('review_berikutnya')->nullable()->index();
            $table->timestamps();

            $table->unique(['pelajar_id', 'kosakata_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progres_hafalans');
    }
};
