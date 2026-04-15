<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\backend\BookBackendController;
use App\Http\Controllers\backend\CategoryBackendController;
use App\Http\Controllers\backend\HeroController;
use App\Http\Controllers\backend\PeminjamanController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\frontend\BookController;
use App\Http\Controllers\frontend\HomeFrontendController;
use App\Http\Controllers\frontend\ProfileFrontendController; // Jangan lupa import di atas
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTE GUEST (BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // TAMBAHKAN INI: Route untuk Register
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ==========================================
// RUTE AUTH UMUM (SEMUA ROLE)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // TARUH ROUTE CETAK DI SINI
    Route::get('/peminjaman/cetak/{id}', [BookController::class, 'print'])->name('peminjaman.print');
    Route::get('/peminjaman/mark-as-printed/{id}', [BookController::class, 'markAsPrinted'])->name('peminjaman.printed');
});


// ==========================================
// RUTE KHUSUS ANGGOTA (FRONTEND)
// ==========================================
Route::middleware(['auth', 'anggota'])->group(function () {
    Route::get('/', [HomeFrontendController::class, 'index'])->name('index');
    Route::get('/book/{id}', [BookController::class, 'show'])->name('book.show');
    Route::post('/book/{id}/borrow', [BookController::class, 'borrow'])->name('book.borrow');
    Route::post('/book/{id}/return', [BookController::class, 'returnBook'])->name('book.return');
    Route::get('/search-books', [HomeFrontendController::class, 'searchBooks'])->name('books.search');
    // Route Profile Frontend
    Route::get('/user/profile', [ProfileFrontendController::class, 'index'])->name('frontend.profile.index');
    Route::post('/user/profile', [ProfileFrontendController::class, 'update'])->name('frontend.profile.update');
});

// ==========================================
// RUTE KHUSUS ADMIN & PETUGAS (BACKEND)
// ==========================================
Route::middleware(['auth', 'admin.petugas'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [HeroController::class, 'index'])->name('dashboard.admin');

    // Manajemen Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/return', [PeminjamanController::class, 'returnBook'])->name('peminjaman.return');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    // Route untuk cetak PDF
    Route::get('/reports/print-pdf', [ReportController::class, 'printPdf'])->name('reports.print-pdf');
    // CRUD User
    Route::resource('user', UserController::class);
    Route::resource('category-admin', CategoryBackendController::class);

    // CRUD Buku (Hanya Petugas)
    Route::middleware(['petugas.only'])->group(function () {
        Route::resource('book-admin', BookBackendController::class);
    });
});
