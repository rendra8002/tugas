<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\frontend\HomeFrontendController;
use Illuminate\Support\Facades\Route;

// Rute untuk Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Rute untuk yang sudah Login (Logout)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('home', HomeFrontendController::class);

    Route::get('/user/profile', function () {
        return view('pages.frontend.profile.index');
    });

     // Nanti Anda bisa menambahkan rute dashboard di sini
    // Route::get('/home', [DashboardController::class, 'index']);
});
