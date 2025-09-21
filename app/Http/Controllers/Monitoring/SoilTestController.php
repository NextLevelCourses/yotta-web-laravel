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

    public function export(string $date): BinaryFileResponse|string
    {
        try {
            $month = substr($date, 5, 2); // hasilnya mm (month) only without yyyy or dd
            $year = substr($date, 0, 4); // hasilnya yyyy (year) only without mm or dd
            $now = date('Y-m-d');
            return Excel::download(new SoilTestExport($month, $year), "soiltest_{$now}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }

    

    

}
