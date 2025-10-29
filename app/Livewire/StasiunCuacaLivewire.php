<?php

namespace App\Livewire;

use App\Models\StasiunCuaca;
use App\Models\ActionLog; 
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Actions\Mqtt\PublishMessage;

class StasiunCuacaLivewire extends Component
{
    public $deviceId = 'N/A';
    public $temperature = 'N/A';
    public $humidity = 'N/A';
    public $co2 = 'N/A';
    public $pm25 = 'N/A';
    public $rainfall = 'N/A';
    public $solarRadiation = 'N/A';
    public $gpsLatitude = 'N/A';
    public $gpsLongitude = 'N/A';
    public $fanStatus = 'OFF';
    public $lastUpdate = 'N/A';

    public function mount()
    {
        $this->fetchLatestData();
    }

    public function fetchLatestData()
    {
        $latestData = StasiunCuaca::latest()->first();

        if ($latestData) {
            $this->deviceId       = $latestData->device_id ?? 'N/A';
            $this->temperature    = $latestData->temperature ?? 'N/A';
            $this->humidity       = $latestData->humidity ?? 'N/A';
            $this->co2            = $latestData->co2 ?? 'N/A';
            $this->pm25           = $latestData->pm25 ?? 'N/A';
            $this->rainfall       = $latestData->rainfall ?? 'N/A';
            $this->solarRadiation = $latestData->solar_radiation ?? 'N/A';
            $this->gpsLatitude    = $latestData->gps_latitude ?? 'N/A';
            $this->gpsLongitude   = $latestData->gps_longitude ?? 'N/A';
            $this->lastUpdate     = $latestData->created_at
                ? $latestData->created_at->diffForHumans()
                : 'N/A';
        }
    }

    public function toggleFan($status)
    {
        // Pastikan hanya status valid yang diterima
        if (!in_array($status, ['ON', 'OFF'])) return;

        // Publish command ke MQTT
        $success = PublishMessage::handle('esp32/fan/command', $status);

        if ($success) {
            $this->fanStatus = $status;

            // Simpan log jika ada user login
            $user = Auth::user();
            if ($user) {
                ActionLog::create([
                    'user_id' => $user->id,
                    'action'  => ($status == 'ON') ? 'FAN_ON' : 'FAN_OFF',
                ]);
            }

            session()->flash('success', 'Perintah kipas berhasil dikirim!');
        } else {
            session()->flash('error', 'Gagal mengirim perintah ke perangkat.');
        }
    }

    public function render()
    {
        return view('livewire.stasiun-cuaca');
    }
}
