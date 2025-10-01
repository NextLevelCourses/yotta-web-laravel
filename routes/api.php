<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Monitoring\LoraController;

Route::prefix('v1')->group(function () {
    Route::prefix('lora')->group(function () {
        Route::get('latest', [LoraController::class, 'HandleGetDataLora']);
    });
});
