<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
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

        // Profil website (CRUD singleton)
        Route::get('/admin/profil', [AdminProfilController::class, 'index'])->name('admin.profil');
        Route::post('/admin/profil', [AdminProfilController::class, 'store'])->name('admin.profil.store');
        Route::put('/admin/profil', [AdminProfilController::class, 'update'])->name('admin.profil.update');

        // Ganti password akun admin
        Route::put('/admin/profil/password', [AdminProfilController::class, 'password'])->name('admin.profil.password');
    });

    // =================== PELAJAR ===================
    Route::middleware('role:pelajar')->group(function () {
        //
    });
});
