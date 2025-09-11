<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\SoilTest;

class SoilTestController extends Controller
{
    public function index()
    {
        // ambil data terbaru soil test
        $data = SoilTest::latest()->get();

        return view('monitoring.soil-test', compact('data'));
    }
}
