<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SolarDomeController extends Controller
{
    /**
     * Tampilkan halaman monitoring Solar Dome.
     */
    public function index()
    {
        // Data dummy (sementara hardcoded)
        $dummyData = [
            'labels' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
            'temperature' => [28, 30, 35, 37, 33, 29],
            'humidity' => [75, 70, 68, 65, 72, 78],
        ];

        return Auth::check() ? view('monitoring.solar-dome', compact('dummyData')) : redirect()->route('login')->with('error', 'Anda perlu login,silahkan login!');
    }

    public function test_snapshot()
    {
        $db = app('firebase.database.solar_dome');
        $snapshot = $db->getReference(config('firebase.database.solar_dome.table'))->getValue();
        // $snapshot = $this->database->getReference($this->table)->getValue();
        return response()->json($snapshot);
    }
}
