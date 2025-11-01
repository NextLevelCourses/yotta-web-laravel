<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Monitoring\SoilTestController;
use App\Http\Controllers\Monitoring\SoilManagController;
use App\Http\Controllers\Monitoring\SolarDomeController;
use App\Http\Controllers\Monitoring\AirQualityController; // ✅ pakai Monitoring, bukan Api
use App\Http\Controllers\Monitoring\LoraController;
use App\Http\Controllers\Monitoring\StasiunCuacaController;
use App\Jobs\LorawanJob;
use App\Models\StasiunCuaca;

// PENTING!!
// Rute untuk halaman Health Check for CircleCI
Route::get('/health', function () {
    return response()->json(['status' => 'okee! test']);
});

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

    // Monitoring (hanya bisa diakses kalau sudah login)
    Route::prefix('monitoring')->group(function () {
        Route::get('air-quality', [AirQualityController::class, 'index'])->name('air-quality');
        Route::prefix('soil-test')->group(function () {
            Route::get('/', [SoilTestController::class, 'index'])->name('soil-test');
            Route::get('/export/{date}', [SoilTestController::class, 'ExportByExcel'])->name('soil-test.export');
            Route::get('/linechart', [SoilTestController::class, 'linechart'])->name('soil-test.linechart');
        });
        Route::prefix('stasiun-cuaca')->group(function () {
            Route::get('/', [StasiunCuacaController::class, 'index'])->name('stasiun-cuaca');
            Route::get('/export/{date}', [StasiunCuacaController::class, 'ExportByExcel'])->name('stasiun-cuaca.export');
            Route::get('/linechart', [StasiunCuacaController::class, 'linechart'])->name('stasiun-cuaca.linechart');
        });
        Route::get('soil-manag', [SoilManagController::class, 'index'])->name('soil-manag');
        Route::prefix('solar-dome')->group(function () {
            Route::get('/', [SolarDomeController::class, 'index'])
                ->name('monitoring.solar-dome');
            Route::get('/export/{date}', [SolarDomeController::class, 'ExportByExcel'])->name('solar-dome.export');
            Route::post('/send-button-control-mode', [SolarDomeController::class, 'send_button_control_mode'])->name('solar-dome.send_button_control_mode');
            Route::post('/send-target-humidity', [SolarDomeController::class, 'send_target_humidity'])->name('solar-dome.send_target_humidity');
        });
        Route::prefix('lora')->group(function () {
            // LorawanJob::dispatch();
            Route::get('/', [LoraController::class, 'index'])->name('monitoring.lora');
            Route::get('/export/{date}', [LoraController::class, 'ExportByExcel'])->name('lora-test.export');
        });
    });
});

// Jangan lupa tambahkan rute untuk admin di sini nanti
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');
});
