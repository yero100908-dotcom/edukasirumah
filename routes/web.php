<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute Publik / Pengunjung Umum (Tanpa Perlu Login)
|--------------------------------------------------------------------------
*/

// Beranda Editorial & Direktori Edukasi
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Pencarian & Filter Topik / Tag
Route::get('/cari', [SearchController::class, 'index'])->name('search');

// Detail Panduan Teknis & Arsitektural (/panduan/{slug})
Route::get('/panduan/{slug}', [ArticleController::class, 'show'])->name('articles.show');

// Pengiriman Komentar Tamu
Route::post('/panduan/{article}/komentar', [CommentController::class, 'store'])->name('comments.store');


/*
|--------------------------------------------------------------------------
| Rute Khusus Admin Panel & Redaksi (auth:admin)
|--------------------------------------------------------------------------
*/

// Autentikasi Admin Terisolasi
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Panel Manajemen Terproteksi
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // CRUD Artikel
        Route::resource('articles', AdminArticleController::class)->except(['show']);

        // Manajemen Pengguna & Role Admin (Superadmin & Editor)
        Route::resource('users', AdminManagementController::class)->only(['index', 'store', 'update', 'destroy']);

        // Manajemen Kategori
        Route::resource('categories', AdminCategoryController::class)->only(['index', 'store', 'update', 'destroy']);

        // Manajemen Tag
        Route::resource('tags', AdminTagController::class)->only(['index', 'store', 'destroy']);

        // Moderasi Komentar
        Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::patch('/comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
        Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // Upload Gambar Rich Text Editor
        Route::post('/upload-image', [AdminUploadController::class, 'uploadImage'])->name('upload.image');
    });
});
