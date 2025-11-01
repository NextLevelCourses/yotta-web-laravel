<?php

namespace App\Http\Controllers\Monitoring;

use App\ExportDataInterface;
use App\Exports\SolarDomeExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class SolarDomeController extends Controller implements ExportDataInterface
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

    private static function HandleGetDataSolarDome($data, int $key): array
    {
        switch ($key) {
            case 0:
                return array(
                    "controlMode" => $data[$key]['controlMode'] ?? 0,
                    "targetHumidity" => $data[$key]['targetHumidity'] ?? '-',
                );
                break;
            case 1:
                return array(
                    "humidity" => $data[$key]['humidity'] ?? 0,
                    "lastUpdate" => Carbon::createFromTimestamp($data[$key]['lastUpdate'] / 1000, config('app.timezone'))->toDateTimeString() ?? '-',
                    // "lastUpdate" => $data[$key]['lastUpdate'] ?? '-',
                    "relayState" => $data[$key]['relayState'] ?? '-',
                    "temperature" => $data[$key]['temperature'] ?? 0,
                );
            default:
                return array('message' => 'invalid key provided');
                break;
        }
    }

    public function test_snapshot()
    {
        $db = app('firebase.database.solar_dome');
        $snapshot = $db->getReference(config('firebase.database.solar_dome.table'))->getValue();

        $solarDomeData = [];
        foreach ($snapshot as $data) {
            array_push($solarDomeData, $data);
        }

        return $this->HandleGetDataSolarDome($solarDomeData, 1);
    }

    public function ExportByExcel(string $date): BinaryFileResponse|string
    {
        try {
            $month = substr($date, 5, 2); // hasilnya mm (month) only without yyyy or dd
            $year = substr($date, 0, 4); // hasilnya yyyy (year) only without mm or dd
            $now = date('Y-m-d');
            return Excel::download(new SolarDomeExport($month, $year), "stasiuncuaca_{$now}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }
}
