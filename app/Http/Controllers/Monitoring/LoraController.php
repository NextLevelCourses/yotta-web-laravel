<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoRa;

class LoraController extends Controller
{
    /**
     * Tampilkan halaman monitoring (Blade)
     */
    public function index()
    {
        return view('monitoring.lora');
    }

    /**
     * Ambil data LoRa terakhir (API)
     */
    public function latest()
    {
        $latest = LoRa::latest()->first();

        if (!$latest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum ada data tersedia'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $latest
        ]);
    }
}
