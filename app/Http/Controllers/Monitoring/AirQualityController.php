<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\AirQuality;
use Illuminate\Support\Facades\Auth;

class AirQualityController extends Controller
{
    public function index()
    {
        $data = AirQuality::latest()->get();
        return Auth::check() ? view('monitoring.air-quality', compact('data')) : redirect()->route('login')->with('error', 'Anda perlu login,silahkan login!');
    }
}
