<?php

namespace App\Livewire;

use Livewire\Component;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Log;

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
                $this->ec = $data['ec'] ?? '--';
                $this->ph = $data['ph'] ?? '--';
                $this->nitrogen = $data['nitrogen'] ?? '--';
                $this->fosfor = $data['fosfor'] ?? '--';
                $this->kalium = $data['kalium'] ?? '--';

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
