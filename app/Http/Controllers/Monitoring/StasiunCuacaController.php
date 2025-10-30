<?php

namespace App\Http\Controllers\Monitoring;

use App\ExportDataInterface;
use App\Exports\StasiunCuacaExport;
use App\Http\Controllers\Controller;
use App\Models\StasiunCuaca;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Log;

class StasiunCuacaController extends Controller implements ExportDataInterface
{
    public function index()
    {
        $device = StasiunCuaca::latest()->first();
        return Auth::check() ? view('monitoring.stasiun-cuaca', compact('device')) : redirect()->route('login')->with('error', 'Anda perlu login,silahkan login!');
    }

    /**
     * @param string $date
     * @return BinaryFileResponse|string
     */
    public function ExportByExcel(string $date): BinaryFileResponse|string
    {
        try {
            $month = substr($date, 5, 2); // hasilnya mm (month) only without yyyy or dd
            $year = substr($date, 0, 4); // hasilnya yyyy (year) only without mm or dd
            $now = date('Y-m-d');
            return Excel::download(new StasiunCuacaExport($month, $year), "stasiuncuaca_{$now}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }
}
