<?php

namespace App\Livewire;

use App\GrafikInterface;
use App\Models\LoRa;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LoraLivewire extends Component implements GrafikInterface
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
        $par_value = '--',
        $phosphorus = '--',
        $potassium = '--',
        $measured_at = '--';

    public function HandleGetDataGrafik(string $param, string $column, string $sort = 'asc'): array
    {
        return LoRa::whereNotNull($param)
            ->orderBy($column, $sort)
            ->limit(10)
            ->pluck($param)
            ->toArray();
    }

    public function mount()
    {
        $this->fetchLora();
    }

    public function fetchLora()
    {
        try {

            //ambil data terbaru dan tampilkan di card
            $lora = LoRa::orderByDesc('id')->first();
            $this->device_id = $lora->device_id ?? '--';
            $this->air_temperature = $lora->air_temperature ?? '--';
            $this->air_humidity = $lora->air_humidity ?? '--';
            $this->soil_pH = $lora->soil_pH ?? '--';
            $this->soil_temperature = $lora->soil_temperature ?? '--';
            $this->soil_conductivity = $lora->soil_conductivity ?? '--';
            $this->soil_humidity = $lora->soil_humidity ?? '--';
            $this->nitrogen = $lora->nitrogen ?? '--';
            $this->par_value = $lora->par_value ?? '--';
            $this->phosphorus = $lora->phosphorus ?? '--';
            $this->potassium = $lora->potassium ?? '--';
            $this->measured_at = $lora->measured_at ?? '--';

            //chart init
            $chart = $this->HandleGetDataGrafik('measured_at', 'id', 'desc');
            if (!empty($chart)) {
                //buat label
                $labels = array_map(function ($date) {
                    if (is_string($date)) return $date;
                    return $date instanceof \Carbon\Carbon ? $date->format('Y-m-d H:i:s') : (string)$date;
                }, $chart);

                //mapping data chart
            }

            //catch error
        } catch (\Exception $error) {
            $this->air_temperature = $this->air_humidity = $this->soil_pH = $this->soil_temperature = $this->soil_conductivity = $this->soil_humidity = $this->nitrogen = $this->par_value = $this->phosphorus = $this->potassium = $this->measured_at = 'Error';
            Log::error('Lorawan Error:', $error->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lora');
    }
}
