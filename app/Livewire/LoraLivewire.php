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
        $measured_at = '--',
        //data grafik
        $labels_charts = [],
        $air_temperature_chart = [],
        $air_humidity_chart = [],
        $soil_pH_chart = [],
        $soil_temperature_chart = [],
        $soil_conductivity_chart = [],
        $soil_humidity_chart = [],
        $nitrogen_chart = [],
        $par_value_chart = [],
        $phosphorus_chart = [],
        $potassium_chart = [];

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
            $chart_labels = $this->HandleGetDataGrafik('measured_at', 'id', 'desc');
            if (!empty($chart_labels)) {
                //buat label
                $labels = array_map(function ($date) {
                    if (is_string($date)) return $date;
                    return $date instanceof \Carbon\Carbon ? $date->format('Y-m-d H:i:s') : (string)$date;
                }, $chart_labels);

                //mapping data chart via hook livewire
                $this->dispatch('chartDataLoraWan', data: [
                    'labels' => $labels,
                    'air_temperature' => $this->HandleGetDataGrafik('air_temperature', 'id', 'desc'),
                    'air_humidity' => $this->HandleGetDataGrafik('air_humidity', 'id', 'desc'),
                    'soil_pH' => $this->HandleGetDataGrafik('soil_pH', 'id', 'desc'),
                    'soil_temperature' => $this->HandleGetDataGrafik('soil_temperature', 'id', 'desc'),
                    'soil_conductivity' => $this->HandleGetDataGrafik('soil_conductivity', 'id', 'desc'),
                    'soil_humidity' => $this->HandleGetDataGrafik('soil_humidity', 'id', 'desc'),
                    'nitrogen' => $this->HandleGetDataGrafik('nitrogen', 'id', 'desc'),
                    'par_value' => $this->HandleGetDataGrafik('par_value', 'id', 'desc'),
                    'phosphorus' => $this->HandleGetDataGrafik('phosphorus', 'id', 'desc'),
                    'potassium' => $this->HandleGetDataGrafik('potassium', 'id', 'desc'),

                ]);
            } else {
                $this->labels_charts = $this->air_temperature_chart = $this->air_humidity_chart = $this->soil_pH_chart = $this->soil_temperature_chart = $this->soil_conductivity_chart = $this->soil_humidity_chart = $this->nitrogen_chart = $this->par_value_chart = $this->phosphorus_chart = $this->potassium_chart = [];
            }

            //catch error
        } catch (\Exception $error) {
            $this->air_temperature = $this->air_humidity = $this->soil_pH = $this->soil_temperature = $this->soil_conductivity = $this->soil_humidity = $this->nitrogen = $this->par_value = $this->phosphorus = $this->potassium = $this->measured_at = 'Error';

            $this->labels_charts = $this->air_temperature_chart = $this->air_humidity_chart = $this->soil_pH_chart = $this->soil_temperature_chart = $this->soil_conductivity_chart = $this->soil_humidity_chart = $this->nitrogen_chart = $this->par_value_chart = $this->phosphorus_chart = $this->potassium_chart = [];

            Log::error('Lorawan Error:', $error->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.lora');
    }
}
