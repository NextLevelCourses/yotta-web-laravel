<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoraLivewire extends Component
{
    public $air_temperature = '--',
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
            $this->air_temperature = 40;
            $this->air_humidity = 20;
            $this->soil_pH = 20;
            $this->soil_temperature = 20;
            $this->soil_conductivity = 20;
            $this->soil_humidity = 20;
            $this->nitrogen = 20;
            $this->phosphorus = 20;
            $this->potassium = 250;
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
