<?php

namespace App\Http\Controllers\Monitoring;

use App\Models\SoilTest;
use App\Exports\SoilTestExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SoilTestController extends Controller
{
    public function index()
    {
        //declare nilai untuk query data monitoring di jam-jam berapa saja
        $hours = [8, 10, 12, 14, 16, 18];

        //get data temperature every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $temperatures = [];
        foreach ($hours as $hour) {
            $temp = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('temperature')
                ->orderBy('measured_at')
                ->value('temperature');
            $temperatures[] = $temp ?? 0;
        }

        //get data humadity every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $humaditys = [];
        foreach ($hours as $hour) {
            $humadity = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('humidity')
                ->orderBy('measured_at')
                ->value('humidity');
            $humaditys[] = $humadity ?? 0;
        }

        //get data ec every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $ecs = [];
        foreach ($hours as $hour) {
            $ec = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('ec')
                ->orderBy('measured_at')
                ->value('ec');
            $ecs[] = $ec ?? 0;
        }

        //get data ph every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $phs = [];
        foreach ($hours as $hour) {
            $ph = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('ph')
                ->orderBy('measured_at')
                ->value('ph');
            $phs[] = $ph ?? 0;
        }

        //get data nitrogen every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $nitrogens = [];
        foreach ($hours as $hour) {
            $nitrogen = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('nitrogen')
                ->orderBy('measured_at')
                ->value('nitrogen');
            $nitrogens[] = $nitrogen ?? 0;
        }

        //get data fosfor every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $fosfors = [];
        foreach ($hours as $hour) {
            $fosfor = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('fosfor')
                ->orderBy('measured_at')
                ->value('fosfor');
            $fosfors[] = $fosfor ?? 0;
        }

        //get data kalium every day at 08:00, 10:00, 12:00, 14:00, 16:00, 18:00
        $kaliums = [];
        foreach ($hours as $hour) {
            $kalium = DB::table('soil_tests')->whereDate('measured_at', now()->format('Y-m-d')) //ambil data hari ini
                ->whereRaw('HOUR(measured_at) = ?', [$hour]) //eksekusi query base dari rules jam yang di tentukan
                ->whereNotNull('kalium')
                ->orderBy('measured_at')
                ->value('kalium');
            $kaliums[] = $kalium ?? 0;
        }

        //mapping data to array
        $data = array(
            'labels' => ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00'],
            'device' => SoilTest::whereNotNull('device_id')->first(),
            'temperature' => $temperatures,
            'humidity' => $humaditys,
            'ec' => $ecs,
            'ph' => $phs,
            'nitrogen' => $nitrogens,
            'fosfor' => $fosfors,
            'kalium' => $kaliums
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
