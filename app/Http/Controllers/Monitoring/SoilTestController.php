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
        // mapping data
        $data = array(
            'soil' => SoilTest::latest()->get(), //ambil data terbaru dari soil test
            'labels' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
            'temperature' => [25, 27, 32, 34, 30, 26],
            'humidity' => [80, 78, 75, 70, 74, 82],
            'ec' => [400, 450, 480, 500, 460, 420],
            'ph' => [8, 20, 10, 10, 6, 2],
            'nitrogen' => [100, 50, 90, 20, 60, 21],
            'fosfor' => [80, 200, 100, 120, 60, 2],
            'kalium' => [180, 200, 100, 120, 62, 21],
        );
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
