<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\AirQuality;

class AirQualityController extends Controller
{
    public function index()
    {
        $data = AirQuality::latest()->get();
        return view('monitoring.air-quality', compact('data'));
    }
}
