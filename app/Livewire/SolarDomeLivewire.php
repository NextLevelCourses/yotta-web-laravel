<?php

namespace App\Livewire;

use App\Models\Solar_dome;
use Carbon\Carbon;
use Livewire\Component;
use App\SolarDomeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SolarDomeLivewire extends Component implements SolarDomeInterface
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

    public static function HandleGetDataSolarDome($data, int $key): array|JsonResponse
    {
        switch ($key) {
            case 0:
                return array(
                    "controlMode" => $data[$key]['controlMode'] ?? 0,
                    "targetHumidity" => $data[$key]['targetHumidity'] ?? '-',
                );
                break;
            case 1:
                return array(
                    "humidity" => $data[$key]['humidity'] ?? 0,
                    "lastUpdate" => Carbon::createFromTimestamp($data[$key]['lastUpdate'] / 1000, config('app.timezone'))->toDateTimeString() ?? '-',
                    // "lastUpdate" => $data[$key]['lastUpdate'] ?? '-',
                    "relayState" => $data[$key]['relayState'] ?? '-',
                    "temperature" => $data[$key]['temperature'] ?? 0,
                );
            default:
                return array();
                break;
        }
    }

    public function fetchSolarDome()
    {
        try {
            $db = app('firebase.database.solar_dome');
            $snapshot = $db->getReference(config('firebase.database.solar_dome.table'))->getValue();

            $solarDomeData = [];
            foreach ($snapshot as $data) {
                array_push($solarDomeData, $data);
            }

            //include data pass to livewire component
            if (!empty($this->HandleGetDataSolarDome($solarDomeData, 1) || !empty($this->HandleGetDataSolarDome($solarDomeData, 0)))) {
                $this->temperature = $this->HandleGetDataSolarDome($solarDomeData, 1)['temperature'];
                $this->humidity = $this->HandleGetDataSolarDome($solarDomeData, 1)['humidity'];
                $this->relayState = $this->HandleGetDataSolarDome($solarDomeData, 1)['relayState'];
                $this->measure_at = $this->HandleGetDataSolarDome($solarDomeData, 1)['lastUpdate'];
                $this->controlMode = $this->HandleGetDataSolarDome($solarDomeData, 0)['controlMode'];
                $this->targetHumidity = $this->HandleGetDataSolarDome($solarDomeData, 0)['targetHumidity'];

                Solar_dome::create([
                    'temperature' => $this->temperature,
                    'humidity' => $this->humidity,
                    'controlMode' => $this->controlMode,
                    'relayState' => $this->relayState,
                    'targetHumidity' => $this->targetHumidity,
                    'measure_at' => $this->measure_at
                ]);
            } else {
                $this->temperature = $this->humidity = $this->controlMode = $this->relayState = $this->targetHumidity = $this->measure_at = 'No Data';
            }
        } catch (\Exception $e) {
            $this->temperature = $this->humidity = $this->controlMode = $this->relayState = $this->targetHumidity = $this->measure_at = 'Error';
        }
    }

    public function render()
    {
        return view('livewire.solar-dome-livewire');
    }
}
