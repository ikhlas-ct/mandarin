<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\KosakataController as AdminKosakataController;
use App\Http\Controllers\Admin\LevelHskController as AdminLevelHskController;
use App\Http\Controllers\Admin\ParagrafController as AdminParagrafController;
use App\Http\Controllers\Admin\PelajarController as AdminPelajarController;
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

        // Kategori kosakata (CRUD: index, create, store, show, edit, update, destroy)
        Route::resource('admin/kategori', AdminKategoriController::class)->names('admin.kategori');

        // Tingkat HSK (CRUD: index, create, store, show, edit, update, destroy)
        Route::resource('admin/level-hsk', AdminLevelHskController::class)->names('admin.level-hsk');

        // Kosakata + contoh kalimat (CRUD) dan import Excel
        // Route import & template HARUS di atas resource, supaya "import" tidak dianggap {kosakata}.
        Route::get('/admin/kosakata/import', [AdminKosakataController::class, 'importForm'])->name('admin.kosakata.import');
        Route::post('/admin/kosakata/import', [AdminKosakataController::class, 'importStore'])->name('admin.kosakata.import.store');
        Route::get('/admin/kosakata/template', [AdminKosakataController::class, 'template'])->name('admin.kosakata.template');
        Route::resource('admin/kosakata', AdminKosakataController::class)
            ->names('admin.kosakata')
            ->parameters(['kosakata' => 'kosakata']);

        // Paragraf (CRUD) + upload/hapus gambar Summernote + import Excel
        // Route import, template, dan gambar HARUS di atas resource, supaya tidak dianggap {paragraf}.
        Route::get('/admin/paragraf/import', [AdminParagrafController::class, 'import'])->name('admin.paragraf.import');
        Route::post('/admin/paragraf/import', [AdminParagrafController::class, 'importStore'])->name('admin.paragraf.import.store');
        Route::get('/admin/paragraf/template', [AdminParagrafController::class, 'template'])->name('admin.paragraf.template');
        Route::post('/admin/paragraf/upload-gambar', [AdminParagrafController::class, 'uploadGambar'])->name('admin.paragraf.upload-gambar');
        Route::post('/admin/paragraf/hapus-gambar', [AdminParagrafController::class, 'hapusGambar'])->name('admin.paragraf.hapus-gambar');
        Route::resource('admin/paragraf', AdminParagrafController::class)
            ->names('admin.paragraf')
            ->parameters(['paragraf' => 'paragraf']);

        // Pelajar + akun loginnya (CRUD: index, create, store, show, edit, update, destroy)
        Route::resource('admin/pelajar', AdminPelajarController::class)->names('admin.pelajar');

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
