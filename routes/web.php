<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Monitoring\AirQualityController; // ✅ pakai Monitoring, bukan Api
use App\Http\Controllers\Monitoring\SoilManagController;
use App\Http\Controllers\Monitoring\SoilTestController;

// Halaman utama (cukup satu kali)
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk Tamu (Guest)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

// Rute untuk User yang sudah Login
Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Monitoring Air Quality (hanya bisa diakses kalau sudah login)
    Route::prefix('monitoring')->group(function () {
        Route::get('air-quality', [AirQualityController::class, 'index'])->name('air-quality');
        Route::prefix('soil-test')->group(function () {
            Route::get('/', [SoilTestController::class, 'index'])->name('soil-test');
            Route::get('/export/{date}', [SoilTestController::class, 'export'])->name('soil-test.export');
        });
        Route::get('soil-manag', [SoilManagController::class, 'index'])->name('soil-manag');
    });
});

// Jangan lupa tambahkan rute untuk admin di sini nanti
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');
});
