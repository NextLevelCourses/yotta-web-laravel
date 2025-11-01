<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Monitoring\LoraController;
use App\Http\Controllers\Monitoring\SolarDomeController;

Route::prefix('v1')->group(function () {
    Route::prefix('lora')->group(function () {
        Route::get('latest', [LoraController::class, 'HandleGetDataLora']);
    });
    Route::get('/solar-dome-test-snapshot', [SolarDomeController::class, 'test_snapshot'])
        ->name('monitoring.solar-dome-test-snapshot');
});
