<?php

namespace App\Livewire;

use App\Models\LoRa;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoraLivewire extends Component
{
    public
        $device_id = '--',
        $air_temperature = '--',
        $air_humidity = '--',
        $soil_pH = '--',
        $soil_temperature = '--',
        $soil_conductivity = '--',
        $soil_humidity = '--',
        $nitrogen = '--',
        $phosphorus = '--',
        $potassium = '--',
        $created_at = '--';

    public function mount()
    {
        $this->fetchLora();
    }

    public function fetchLora()
    {
        try {
            $lora = LoRa::orderByDesc('id')->first();
            $this->device_id = $lora->device_id ?? '--';
            $this->air_temperature = $lora->air_temperature ?? '--';
            $this->air_humidity = $lora->air_humidity ?? '--';
            $this->soil_pH = $lora->soil_pH ?? '--';
            $this->soil_temperature = $lora->soil_temperature ?? '--';
            $this->soil_conductivity = $lora->soil_conductivity ?? '--';
            $this->soil_humidity = $lora->soil_humidity ?? '--';
            $this->nitrogen = $lora->nitrogen ?? '--';
            $this->phosphorus = $lora->phosphorus ?? '--';
            $this->potassium = $lora->potassium ?? '--';
            $this->created_at = now();
        } catch (\Exception $error) {
            $this->air_temperature = $this->air_humidity = $this->soil_pH = $this->soil_temperature = $this->soil_conductivity = $this->soil_humidity = $this->nitrogen = $this->phosphorus = $this->potassium = $this->created_at = 'Error';
            Log::error('Lorawan Error:', $error->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lora');
    }
}
