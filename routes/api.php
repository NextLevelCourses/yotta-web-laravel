<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Monitoring\LoraController;

Route::get('/loras/latest', [LoraController::class, 'latest']);
