<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\SoilTest;

class SoilManagController extends Controller
{
    public function index()
    {
        // ambil data terbaru soil test
        // $data = SoilManag::latest()->get();

        // return view('monitoring.soil-manag', compact('data'));
        return view('monitoring.soil-manag');
    }
}
