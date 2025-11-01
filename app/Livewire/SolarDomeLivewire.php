<?php

namespace App\Livewire;

use Livewire\Component;

class SolarDomeLivewire extends Component
{
    public $temperature = '--',
        $humidity = '--',
        $controlMode = '--',
        $relayState = '--',
        $targetHumidity = '--',
        $measure_at = '--';

    public function mount()
    {
        $this->fetchSolarDome();
    }

    public function fetchSolarDome()
    {
        try {
        } catch (\Exception $e) {
            $this->temperature = $this->humidity = $this->controlMode = $this->relayState = $this->targetHumidity = $this->measure_at = 'Error';
        }
    }

    public function render()
    {
        return view('livewire.solar-dome-livewire');
    }
}
