<?php

namespace App\Livewire;

use App\GrafikInterface;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\StasiunCuaca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Google\Cloud\Firestore\FirestoreClient;

class StasiunCuacaLivewire extends Component implements GrafikInterface
{
    // ===== Data Sensor =====
    public
        $device_id = '--',
        $temperature = '--',
        $humidity = '--',
        $rainfall = '--',
        $solar_radiation = '--',
        $co2 = '--',
        $nh3 = '--',
        $no2 = '--',
        $o3 = '--',
        $pm10 = '--',
        $pm25 = '--',
        $so2 = '--',
        $tvoc = '--',
        $tanggal = '--',
        $jam = '--',

        // ===== Data Grafik =====
        $labels_charts = [],
        $temperature_chart = [],
        $humidity_chart = [],
        $rainfall_chart = [],
        $solar_chart = [],
        $co2_chart = [],
        $nh3_chart = [],
        $no2_chart = [],
        $o3_chart = [],
        $pm10_chart = [],
        $pm25_chart = [],
        $so2_chart = [],
        $tvoc_chart = [];

    // ===== Ambil Data untuk Grafik =====
    public function HandleGetDataGrafik(string $param, string $column, string $sort = 'asc'): array
    {
        return StasiunCuaca::whereNotNull($param)
            ->orderBy($column, $sort)
            ->limit(10)
            ->pluck($param)
            ->toArray();
    }

    // ===== Lifecycle =====
    public function mount()
    {
        $this->fetchData();
    }

    // ===== Format Data untuk Disimpan =====
    public function handleStoreDataCollection(array $data): array
    {
        return [
            'device_id'        => $data['Device_ID'] ?? 'Unknown Device',
            'temperature'      => $data['temperature'] ?? 0,
            'humidity'         => $data['humidity'] ?? 0,
            'rainfall'         => $data['rainfall'] ?? 0,
            'solar_radiation'  => $data['solar_radiation'] ?? 0,
            'co2'              => $data['co2'] ?? 0,
            'nh3'              => $data['nh3'] ?? 0,
            'no2'              => $data['no2'] ?? 0,
            'o3'               => $data['o3'] ?? 0,
            'pm10'             => $data['pm10'] ?? 0,
            'pm25'             => $data['pm25'] ?? 0,
            'so2'              => $data['so2'] ?? 0,
            'tvoc'             => $data['tvoc'] ?? 0,
            'tanggal'          => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d'),
            'jam'              => Carbon::now()->timezone(config('app.timezone'))->format('H:i:s'),
            'created_at'       => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
        ];
    }

    // ===== Ambil Data dari Firestore =====
    public function fetchData()
    {
        try {
            /** @var FirestoreClient $firestore */
            $firestore = app('firebase.firestore.wheater_station');

            // Ambil dari path: stasiun_cuaca/lates
            $snapshot = $firestore->collection(config('firebase.database.wheater_station.table'))
                ->document('lates')
                ->snapshot();

            // Ambil label grafik dari database lokal
            $chart_label = $this->HandleGetDataGrafik('created_at', 'id', 'desc');

            // ===== Jika Data Sensor Ada =====
            if ($snapshot->exists()) {
                $data = $snapshot->data();

                $this->device_id        = $data['Device_ID'] ?? '--';
                $this->temperature      = $data['temperature'] ?? '--';
                $this->humidity         = $data['humidity'] ?? '--';
                $this->rainfall         = $data['rainfall'] ?? '--';
                $this->solar_radiation  = $data['solar_radiation'] ?? '--';
                $this->co2              = $data['co2'] ?? '--';
                $this->nh3              = $data['nh3'] ?? '--';
                $this->no2              = $data['no2'] ?? '--';
                $this->o3               = $data['o3'] ?? '--';
                $this->pm10             = $data['pm10'] ?? '--';
                $this->pm25             = $data['pm25'] ?? '--';
                $this->so2              = $data['so2'] ?? '--';
                $this->tvoc             = $data['tvoc'] ?? '--';
                $this->tanggal          = Carbon::now()->format('Y-m-d');
                $this->jam              = Carbon::now()->format('H:i:s');

                // Simpan data ke database setiap polling
                DB::table('stasiun_cuacas')->insert($this->handleStoreDataCollection($data));
            } else {
                $this->device_id = $this->temperature = $this->humidity = $this->rainfall =
                    $this->solar_radiation = $this->co2 = $this->nh3 = $this->no2 = $this->o3 =
                    $this->pm10 = $this->pm25 = $this->so2 = $this->tvoc = 'Not Found';
            }

            // ===== Update Data Grafik =====
            if (!empty($chart_label)) {
                $labels = array_map(function ($date) {
                    return is_string($date)
                        ? $date
                        : ($date instanceof Carbon ? $date->format('Y-m-d H:i:s') : (string) $date);
                }, $chart_label);

                // Kirim data ke frontend (JS chart)
                $this->dispatch('chartDataStasiunCuaca', data: [
                    'labels'        => $labels,
                    'temperature'   => $this->HandleGetDataGrafik('temperature', 'id', 'desc'),
                    'humidity'      => $this->HandleGetDataGrafik('humidity', 'id', 'desc'),
                    'rainfall'      => $this->HandleGetDataGrafik('rainfall', 'id', 'desc'),
                    'solar'         => $this->HandleGetDataGrafik('solar_radiation', 'id', 'desc'),
                    'co2'           => $this->HandleGetDataGrafik('co2', 'id', 'desc'),
                    'nh3'           => $this->HandleGetDataGrafik('nh3', 'id', 'desc'),
                    'no2'           => $this->HandleGetDataGrafik('no2', 'id', 'desc'),
                    'o3'            => $this->HandleGetDataGrafik('o3', 'id', 'desc'),
                    'pm10'          => $this->HandleGetDataGrafik('pm10', 'id', 'desc'),
                    'pm25'          => $this->HandleGetDataGrafik('pm25', 'id', 'desc'),
                    'so2'           => $this->HandleGetDataGrafik('so2', 'id', 'desc'),
                    'tvoc'          => $this->HandleGetDataGrafik('tvoc', 'id', 'desc'),
                ]);
            } else {
                $this->labels_charts = [];
                $this->temperature_chart = $this->humidity_chart = $this->rainfall_chart =
                    $this->solar_chart = $this->co2_chart = $this->nh3_chart = $this->no2_chart =
                    $this->o3_chart = $this->pm10_chart = $this->pm25_chart = $this->so2_chart =
                    $this->tvoc_chart = [];
            }
        } catch (\Throwable $e) {
            $this->device_id = $this->temperature = $this->humidity = $this->rainfall =
                $this->solar_radiation = $this->co2 = $this->nh3 = $this->no2 = $this->o3 =
                $this->pm10 = $this->pm25 = $this->so2 = $this->tvoc = 'Error';

            $this->labels_charts = [];
            $this->temperature_chart = $this->humidity_chart = $this->rainfall_chart =
                $this->solar_chart = $this->co2_chart = $this->nh3_chart = $this->no2_chart =
                $this->o3_chart = $this->pm10_chart = $this->pm25_chart = $this->so2_chart =
                $this->tvoc_chart = [];

            Log::error('Firestore error (StasiunCuaca): ' . $e->getMessage());
        }
    }

    // ===== View =====
    public function render()
    {
        return view('livewire.stasiun-cuaca');
    }
}
