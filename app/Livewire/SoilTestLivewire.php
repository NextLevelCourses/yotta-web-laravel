<?php

namespace App\Livewire;

use Livewire\Component;
use Kreait\Firebase\Contract\Firestore;
use Illuminate\Support\Facades\Log;

class SoilTestLivewire extends Component
{
    public $temperature = '--';
    public $humidity = '--';
    public $airQuality = '--';

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

            // [PERBAIKAN] Path disesuaikan agar cocok persis dengan struktur di Firestore
            $snapshot = $database->collection('artifacts')
                                 ->document('default-app-id')
                                 ->collection('public')
                                 ->document('data')
                                 ->collection('sensor')
                                 ->document('latest')
                                 ->snapshot();

            if ($snapshot->exists()) {
                $data = $snapshot->data();

                $this->temperature = isset($data['temperature'])
                    ? number_format((float) $data['temperature'], 1)
                    : '--';

                $this->humidity = isset($data['humidity'])
                    ? number_format((float) $data['humidity'], 1)
                    : '--';

                $this->airQuality = $data['airQuality'] ?? '--';
            } else {
                $this->temperature = 'Not Found';
                $this->humidity = 'Not Found';
                $this->airQuality = 'Not Found';
            }
        } catch (\Throwable $e) {
            // Jika ada error, properti akan menampilkan 'Error'
            $this->temperature = 'Error';
            $this->humidity = 'Error';
            $this->airQuality = 'Error';

            // Mencatat error ke dalam file log Laravel untuk debugging
            Log::error('Firestore error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Pastikan file view Anda berada di 'resources/views/livewire/soil-test.blade.php'
        return view('livewire.soil-test');
    }
}

