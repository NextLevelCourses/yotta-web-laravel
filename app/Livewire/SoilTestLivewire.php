<?php

namespace App\Livewire;

use App\GrafikInterface;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\SoilTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Firestore;

class SoilTestLivewire extends Component implements GrafikInterface
{
    public //data sensor
        $devices_id = '--',
        $temperature = '--',
        $humidity = '--',
        $ec = '--',
        $ph = '--',
        $nitrogen = '--',
        $fosfor = '--',
        $kalium = '--',
        //data grafik
        $labels_charts = [],
        $temperature_chart = [],
        $humidity_chart = [],
        $ec_chart = [],
        $ph_chart = [],
        $nitrogen_chart = [],
        $fosfor_chart = [],
        $kalium_chart = [];

    public function HandleGetDataGrafik(string $param, string $column, string $sort = 'asc'): array
    {
        return SoilTest::whereNotNull($param)
            ->orderBy($column, $sort)
            ->limit(10)
            ->pluck($param)
            ->toArray();
    }

    public function mount()
    {
        $this->fetchData();
    }

    public function handleStoreDataCollection(array $data): array
    {
        return array(
            'device_id' => $data['Device_ID'] ?? 'Unknown Device',
            'temperature' => $data['temperature'] ?? 0,
            'humidity' => $data['humidity'] ?? 0,
            'ph' => $data['ph'] ?? 0,
            'ec' => $data['Konduktivitas'] ?? 0,
            'nitrogen' => $data['nitrogen'] ?? 0,
            'fosfor' => $data['Fosfor'] ?? 0,
            'kalium' => $data['Kalium'] ?? 0,
            'measured_at' => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
        );
    }

    public function fetchData()
    {
        try {
            /** @var Firestore $firestore */
            $firestore = app('firebase.firestore');
            $database = $firestore->database();

            // Ambil dari path: soil_data/latest
            $snapshot = $database->collection('soil_data')
                ->document('latest')
                ->snapshot();

            //get data label of chart grafik
            $chart_label = $this->HandleGetDataGrafikSoilTest('measured_at', 'id', 'desc');

            //validate data sensor exists
            if ($snapshot->exists()) {
                $data = $snapshot->data();
                $this->devices_id = $data['Device_ID'] ?? '--';
                $this->temperature = $data['temperature'] ?? '--';
                $this->humidity = $data['humidity'] ?? '--';
                $this->ec = $data['Konduktivitas'] ?? '--';
                $this->ph = $data['ph'] ?? '--';
                $this->nitrogen = $data['nitrogen'] ?? '--';
                $this->fosfor = $data['Fosfor'] ?? '--';
                $this->kalium = $data['Kalium'] ?? '--';
                DB::table('soil_tests')->insert($this->handleStoreDataCollection($data)); //store data history every 3 seconds
            } else {
                $this->devices_id = $this->temperature = $this->humidity = $this->ec = $this->ph =
                    $this->nitrogen = $this->fosfor = $this->kalium = 'Not Found';
            }

            //validate data grafik exists
            if (!empty($chart_label)) {
                //label setup
                $labels = array_map(function ($date) {
                    if (is_string($date)) return $date;
                    return $date instanceof \Carbon\Carbon ? $date->format('Y-m-d H:i:s') : (string)$date;
                }, $chart_label);

                //mapping data via hook livewire
                $this->dispatch('chartDataSoilTest', data: [
                    'labels' => $labels,
                    'temperature' => $this->HandleGetDataGrafikSoilTest('temperature', 'id', 'desc'),
                    'humidity' => $this->HandleGetDataGrafikSoilTest('humidity', 'id', 'desc'),
                    'ec' => $this->HandleGetDataGrafikSoilTest('ec', 'id', 'desc'),
                    'ph' => $this->HandleGetDataGrafikSoilTest('ph', 'id', 'desc'),
                    'nitrogen' => $this->HandleGetDataGrafikSoilTest('nitrogen', 'id', 'desc'),
                    'fosfor' => $this->HandleGetDataGrafikSoilTest('fosfor', 'id', 'desc'),
                    'kalium' => $this->HandleGetDataGrafikSoilTest('kalium', 'id', 'desc')
                ]);
            } else {
                $this->labels_charts = $this->temperature_chart = $this->humidity_chart = $this->ec_chart = $this->ph_chart = $this->nitrogen_chart = $this->fosfor_chart = $this->kalium_chart = [];
            }

            //catch error if something wrong from data soil test
        } catch (\Throwable $e) {
            $this->devices_id = $this->temperature = $this->humidity = $this->ec = $this->ph =
                $this->nitrogen = $this->fosfor = $this->kalium = 'Error';

            $this->labels_charts = $this->temperature_chart = $this->humidity_chart = $this->ec_chart = $this->ph_chart = $this->nitrogen_chart = $this->fosfor_chart = $this->kalium_chart = [];

            Log::error('Firestore error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.soil-test');
    }
}
