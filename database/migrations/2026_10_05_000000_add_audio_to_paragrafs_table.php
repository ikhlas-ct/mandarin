<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paragrafs', function (Blueprint $table) {
            // Path relatif di disk "public", mis. paragraf/audio/xxxx.mp3
            $table->string('audio')->nullable()->after('penjelasan_tata_bahasa');
        });
    }

    public function down(): void
    {
        Schema::table('paragrafs', function (Blueprint $table) {
            $table->dropColumn('audio');
        });
    }
};
