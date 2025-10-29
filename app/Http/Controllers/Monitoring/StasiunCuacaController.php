<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\StasiunCuaca;
use Illuminate\Support\Facades\Auth;

class StasiunCuacaController extends Controller
{
    public function index()
    {
        $data = StasiunCuaca::latest()->get();
        return Auth::check() ? view('monitoring.stasiun-cuaca', compact('data')) : redirect()->route('login')->with('error', 'Anda perlu login,silahkan login!');
    }
}
