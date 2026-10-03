<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            // Satu user admin = satu profil admin. Jika user dihapus, profilnya ikut terhapus.
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nama', 50);
            $table->string('jabatan', 50)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->text('keterangan')->nullable();   // bio / deskripsi singkat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
