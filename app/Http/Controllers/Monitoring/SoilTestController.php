<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\SoilTest;
use App\Exports\SoilTestExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SoilTestController extends Controller
{
    public function index()
    {
        // ambil data terbaru soil test
        $data = SoilTest::latest()->get();


        return view('monitoring.soil-test', compact('data'));
    }

    public function export(): BinaryFileResponse|string
    {
        try {
            return Excel::download(new SoilTestExport, 'soiltest.xlsx');
        } catch (\Exception $e) {
            Log::error('Firestore error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }

    

    

}
