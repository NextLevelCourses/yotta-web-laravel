<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GreenhouseController extends Controller
{
    /**
     * Tampilkan halaman monitoring Greenhouse.
     */
    public function index()
    {
        // Data dummy
        $dummyData = [
            'labels' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
            'temperature' => [25, 27, 32, 34, 30, 26],
            'humidity' => [80, 78, 75, 70, 74, 82],
            'co2' => [400, 450, 480, 500, 460, 420],
        ];

        return view('monitoring.greenhouse', compact('dummyData'));
    }
}
