<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\WebsiteSettingController as AdminWebsiteSettingController;
use App\Http\Controllers\Login\AuthController;
use Illuminate\Support\Facades\Route;

// =================== Auth Routes ===================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login_post'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/asd', [AuthController::class, 'login'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // =================== ADMIN ===================
    Route::middleware('role:admin')->group(function () {

        // Dashboard
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Profil admin
        Route::get('/admin/profil', [AdminProfilController::class, 'index'])->name('admin.profil');
        Route::post('/admin/profil', [AdminProfilController::class, 'store'])->name('admin.profil.store');
        Route::put('/admin/profil', [AdminProfilController::class, 'update'])->name('admin.profil.update');

        // Ganti password akun admin
        Route::put('/admin/profil/password', [AdminProfilController::class, 'password'])->name('admin.profil.password');

        // Pengaturan website (hanya edit/update, satu baris di tabel website_settings)
        Route::get('/admin/pengaturan-website', [AdminWebsiteSettingController::class, 'index'])->name('admin.pengaturan');
        Route::put('/admin/pengaturan-website', [AdminWebsiteSettingController::class, 'update'])->name('admin.pengaturan.update');
    });

    // =================== PELAJAR ===================
    Route::middleware('role:pelajar')->group(function () {
        //
    });
});
