<?php

namespace App\Livewire;

use App\Models\SoilTest;
use Livewire\Component;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SoilTestLivewire extends Component
{
    public $temperature = '--';
    public $humidity = '--';
    public $ec = '--';
    public $ph = '--';
    public $nitrogen = '--';
    public $fosfor = '--';
    public $kalium = '--';

    public function mount()
    {
        $this->fetchData();
    }

    public function handleStoreDataCollection(array $data): array
    {
        return array(
            'temperature' => $data['temperature'] ?? 0,
            'humidity' => $data['humidity'] ?? 0,
            'ph' => $data['ph'] ?? 0,
            'ec' => $data['Konduktivitas'] ?? 0,
            'nitrogen' => $data['nitrogen'] ?? 0,
            'fosfor' => $data['Fosfor'] ?? 0,
            'kalium' => $data['Kalium'] ?? 0,
            'measured_at' => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
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


            if ($snapshot->exists()) {
                $data = $snapshot->data();
                $this->temperature = $data['temperature'] ?? '--';
                $this->humidity = $data['humidity'] ?? '--';
                $this->ec = $data['Konduktivitas'] ?? '--';
                $this->ph = $data['ph'] ?? '--';
                $this->nitrogen = $data['nitrogen'] ?? '--';
                $this->fosfor = $data['Fosfor'] ?? '--';
                $this->kalium = $data['Kalium'] ?? '--';
                SoilTest::create($this->handleStoreDataCollection($data)); //store data history every 3 seconds

                // Kirim ke JS
                // $this->dispatch('sensorDataUpdated', $data);
                // Kirim event ke JavaScript dengan data yang diperbarui
                $this->dispatch('updateKnobs', [
                    'temperature' => $this->temperature,
                    'humidity' => $this->humidity,
                    'ec' => $this->ec,
                    'ph' => $this->ph,
                    'nitrogen' => $this->nitrogen,
                    'fosfor' => $this->fosfor,
                    'kalium' => $this->kalium,
                ]);
            } else {
                $this->temperature = $this->humidity = $this->ec = $this->ph =
                    $this->nitrogen = $this->fosfor = $this->kalium = 'Not Found';
            }
        } catch (\Throwable $e) {
            $this->temperature = $this->humidity = $this->ec = $this->ph =
                $this->nitrogen = $this->fosfor = $this->kalium = 'Error';

            Log::error('Firestore error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.soil-test');
    }
}
