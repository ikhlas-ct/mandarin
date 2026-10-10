<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\GrupKosakataController as AdminGrupKosakataController;
use App\Http\Controllers\Admin\KosakataController as AdminKosakataController;
use App\Http\Controllers\Admin\LevelHskController as AdminLevelHskController;
use App\Http\Controllers\Admin\ParagrafController as AdminParagrafController;
use App\Http\Controllers\Admin\PelajarController as AdminPelajarController;
use App\Http\Controllers\Admin\ProfilController as AdminProfilController;
use App\Http\Controllers\Admin\WebsiteSettingController as AdminWebsiteSettingController;
use App\Http\Controllers\Login\AuthController;
use App\Http\Controllers\Pelajar\DashboardController as PelajarDashboardController;
use App\Http\Controllers\Pelajar\HafalanController as PelajarHafalanController;
use App\Http\Controllers\Pelajar\LatihanController as PelajarLatihanController;
use App\Http\Controllers\PelajarFlashcardController;
use App\Http\Controllers\PelajarKosakataController;
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

        // Grup kosakata (CRUD: index, create, store, show, edit, update, destroy) + generator soal latihan.
        // Parameter diganti 'grup' supaya cocok dengan type-hint GrupKosakata $grup di controller.
        Route::resource('admin/grup-kosakata', AdminGrupKosakataController::class)
            ->names('admin.grup-kosakata')
            ->parameters(['grup-kosakata' => 'grup']);

        // Buat soal otomatis (A/B/C/D) dari kata-kata di grup.
        Route::post('/admin/grup-kosakata/{grup}/generate', [AdminGrupKosakataController::class, 'generate'])->name('admin.grup-kosakata.generate');
        // Jaga-jaga kalau URL generate terbuka lewat GET (refresh/history): arahkan ke detail grup.
        Route::get('/admin/grup-kosakata/{grup}/generate', fn ($grup) => redirect()->route('admin.grup-kosakata.show', $grup));

        // Kelola latihan hasil generate: edit pengaturan, aktifkan/nonaktifkan, dan hapus.
        Route::post('/admin/grup-kosakata/{grup}/latihan/{grupSoal}/tautkan', [AdminGrupKosakataController::class, 'tautkanLatihan'])->name('admin.grup-kosakata.latihan.tautkan');
        Route::get('/admin/grup-kosakata/{grup}/latihan/{grupSoal}/edit', [AdminGrupKosakataController::class, 'editLatihan'])->name('admin.grup-kosakata.latihan.edit');
        Route::put('/admin/grup-kosakata/{grup}/latihan/{grupSoal}', [AdminGrupKosakataController::class, 'updateLatihan'])->name('admin.grup-kosakata.latihan.update');
        Route::patch('/admin/grup-kosakata/{grup}/latihan/{grupSoal}/toggle', [AdminGrupKosakataController::class, 'toggleLatihan'])->name('admin.grup-kosakata.latihan.toggle');
        Route::delete('/admin/grup-kosakata/{grup}/latihan/{grupSoal}', [AdminGrupKosakataController::class, 'destroyLatihan'])->name('admin.grup-kosakata.latihan.destroy');

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

        // Dashboard
        Route::get('/pelajar/dashboard', [PelajarDashboardController::class, 'index'])->name('pelajar.dashboard');

        // Kosakata (pelajar hanya melihat + memindahkan status hafalan; tanpa create/edit/import)
        Route::get('/pelajar/kosakata', [PelajarKosakataController::class, 'index'])->name('pelajar.kosakata.index');
        Route::post('/pelajar/kosakata/status', [PelajarKosakataController::class, 'updateStatus'])->name('pelajar.kosakata.status');
        Route::get('/pelajar/kosakata/{kosakata}', [PelajarKosakataController::class, 'show'])->name('pelajar.kosakata.show');

        // Flashcard: review harian (kata jatuh tempo) + latihan bebas
        Route::get('/pelajar/flashcard', [PelajarFlashcardController::class, 'index'])->name('pelajar.flashcard.index');
        Route::get('/pelajar/flashcard/mulai', [PelajarFlashcardController::class, 'mulai'])->name('pelajar.flashcard.mulai');
        Route::post('/pelajar/flashcard/jawab', [PelajarFlashcardController::class, 'jawab'])->name('pelajar.flashcard.jawab');

        // Hafalan saya: jumlah ingat / lupa / berikutnya + pencarian kata + riwayat review
        Route::get('/pelajar/hafalan', [PelajarHafalanController::class, 'index'])->name('pelajar.hafalan.index');
        // Data review (riwayat) satu kata, dipakai popup di halaman Hafalan Saya (JSON)
        Route::get('/pelajar/hafalan/{kosakata}/riwayat', [PelajarHafalanController::class, 'riwayat'])->name('pelajar.hafalan.riwayat');

        // Latihan & ujian (grup soal hasil generator). Saat jawaban dikumpulkan, hafalan
        // (progres_hafalans) dan riwayat_reviews (sumber 'soal') diperbarui otomatis.
        Route::get('/pelajar/latihan', [PelajarLatihanController::class, 'index'])->name('pelajar.latihan.index');
        Route::get('/pelajar/latihan/{grupSoal}', [PelajarLatihanController::class, 'show'])->name('pelajar.latihan.show');
        Route::post('/pelajar/latihan/{grupSoal}/mulai', [PelajarLatihanController::class, 'mulai'])->name('pelajar.latihan.mulai');

        // Mengerjakan -> kumpulkan -> hasil
        Route::get('/pelajar/pengerjaan/{hasilUjian}', [PelajarLatihanController::class, 'kerjakan'])->name('pelajar.pengerjaan.kerjakan');
        Route::post('/pelajar/pengerjaan/{hasilUjian}', [PelajarLatihanController::class, 'kumpulkan'])->name('pelajar.pengerjaan.kumpulkan');
        Route::get('/pelajar/pengerjaan/{hasilUjian}/hasil', [PelajarLatihanController::class, 'hasil'])->name('pelajar.pengerjaan.hasil');
    });
});
