<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kosakatas', function (Blueprint $table) {
            // Penjelasan kegunaan kata: kapan / dalam situasi apa kata ini dipakai.
            $table->text('kegunaan')->nullable()->after('arti_indonesia');
        });
    }

    public function down(): void
    {
        Schema::table('kosakatas', function (Blueprint $table) {
            $table->dropColumn('kegunaan');
        });
    }
};
