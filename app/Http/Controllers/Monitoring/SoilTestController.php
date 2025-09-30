<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\SoilTest;
use App\Exports\SoilTestExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\SoiltestInterface;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SoilTestController extends Controller implements SoiltestInterface
{
    public function HandleGetDataGrafikSoilTest(string $param, string $column, string $sort = 'asc'): array
    {
        return SoilTest::whereNotNull($param)
            ->orderBy($column, $sort)
            ->limit(10)
            ->pluck($param)
            ->toArray();
    }

    public function index()
    {
        $get_label = $this->HandleGetDataGrafikSoilTest('measured_at', 'id', parent::LATEST);
        $labels = array_map(function ($date) {
            if (is_string($date)) return $date;
            return $date instanceof \Carbon\Carbon ? $date->format('Y-m-d H:i:s') : (string)$date;
        }, $get_label);

        //mapping data to array
        $data = array(
            'labels' => $labels,
            'device' => SoilTest::whereNotNull('device_id',)->orderByDesc('id')->first(),
            'temperature' => $this->HandleGetDataGrafikSoilTest('temperature', 'id', parent::LATEST),
            'humidity' => $this->HandleGetDataGrafikSoilTest('humidity', 'id', parent::LATEST),
            'ec' => $this->HandleGetDataGrafikSoilTest('ec', 'id', parent::LATEST),
            'ph' => $this->HandleGetDataGrafikSoilTest('ph', 'id', parent::LATEST),
            'nitrogen' => $this->HandleGetDataGrafikSoilTest('nitrogen', 'id', parent::LATEST),
            'fosfor' => $this->HandleGetDataGrafikSoilTest('fosfor', 'id', parent::LATEST),
            'kalium' => $this->HandleGetDataGrafikSoilTest('kalium', 'id', parent::LATEST)
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
