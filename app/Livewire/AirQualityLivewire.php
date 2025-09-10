<?php

namespace App\Livewire;

use App\Models\AirQuality;
use App\Models\ActionLog; 
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Actions\Mqtt\PublishMessage;

class AirQualityLivewire extends Component
{
    public $temperature = 'N/A';
    public $humidity = 'N/A';
    public $airQuality = 'N/A';
    public $fanStatus = 'OFF';
    public $lastUpdate = 'N/A';

    public function mount()
    {
        $this->fetchLatestData();
    }

    public function fetchLatestData()
    {
        $latestData = AirQuality::latest()->first();

        if ($latestData) {
            $this->temperature = $latestData->temperature;
            $this->humidity    = $latestData->humidity;
            $this->airQuality  = $latestData->air_quality;
            $this->fanStatus   = $latestData->fan_status;
            $this->lastUpdate  = $latestData->created_at->diffForHumans();
        }
    }

    public function toggleFan($status)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            session()->flash('error', 'Hanya admin yang dapat mengontrol perangkat.');
            return;
        }

        if (!in_array($status, ['ON', 'OFF'])) return;

        // Publish command ke MQTT
        $success = PublishMessage::handle('esp32/fan/command', $status);

        if ($success) {
            $this->fanStatus = $status;

            ActionLog::create([
                'user_id' => $user->id,
                'action'  => ($status == 'ON') ? 'FAN_ON' : 'FAN_OFF',
            ]);

            session()->flash('success', 'Perintah kipas berhasil dikirim!');
        } else {
            session()->flash('error', 'Gagal mengirim perintah ke perangkat.');
        }
    }

    public function render()
    {
        return view('livewire.air-quality');
    }
}
