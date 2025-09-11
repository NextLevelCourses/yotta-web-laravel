<?php

namespace App\Livewire;

use App\Models\AirQuality;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Actions\Mqtt\PublishMessage;
use App\Models\ActionLog;

class AirQualityApi extends Component
{
    public $temperature = 'N/A';
    public $humidity = 'N/A';
    public $airQuality = 0;
    public $lastUpdate = 'N/A';
    public $fanStatus = 'off'; // Default status

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
            $this->airQuality  = $latestData->air_quality ?? 0;
            $this->lastUpdate  = $latestData->created_at->diffForHumans();
        }
        
        // Kirim event ke JavaScript untuk update chart
        $this->dispatch('dataUpdated', [
            'airQuality' => $this->airQuality,
        ]);
    }

    public function toggleFan($status)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            session()->flash('error', 'Hanya admin yang dapat mengontrol perangkat.');
            return;
        }

        // Sesuaikan dengan format pesan Arduino ('1' untuk ON, '0' untuk OFF)
        $message = ($status === 'on') ? '1' : '0';
        // Topic HARUS sama dengan yang ada di Arduino
        $topic = 'tes/topic/fan_status';  

        $success = PublishMessage::handle($topic, $message);

        if ($success) {
            $this->fanStatus = $status;
            
            // Catat riwayat aksi ke database
            ActionLog::create([
                'user_id' => $user->id,
                'action'  => 'FAN_CONTROL',
                'details' => "Fan turned {$status} by {$user->name}",
            ]);

            session()->flash('success', 'Perintah berhasil dikirim!');
        } else {
            session()->flash('error', 'Gagal mengirim perintah ke perangkat.');
        }
    }

    public function render()
    {
        return view('livewire.air-quality');
    }
}
