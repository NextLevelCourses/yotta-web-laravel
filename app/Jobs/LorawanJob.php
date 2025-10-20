<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\LoRa;
use App\LoraInterface;
use App\Http\Controllers\Controller;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LorawanJob extends Controller implements ShouldQueue, LoraInterface
{
    use Queueable;

    private function HandleIncludePartOfObjectInsideArray($raw): array|string
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //ambil data last
        $data = '';
        foreach ($jsonObjects as $value) {
            if (!empty($value)) {
                if (
                    Carbon::parse($value['result']['uplink_message']['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H') >= Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H')
                ) {
                    $data = $value['result']['uplink_message']['decoded_payload'];
                }
            } else {
                return 0;
            }
        }

        //map data
        $latest = array(
            'air_humidity' => $data['air_humidity'],
            'air_temperature' => $data['air_temperature'],
            'nitrogen' => $data['nitrogen'],
            'par_value' => $data['par_value'],
            'phosphorus' => $data['phosphorus'],
            'potassium' => $data['potassium'],
            'soil_conductivity' => $data['soil_conductivity'],
            'soil_humidity' => $data['soil_humidity'],
            'soil_pH' => $data['soil_pH'],
            'soil_temperature' => $data['soil_temperature'],
            'measured_at' => Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'),
        );

        //store data
        Lora::create($latest);
        return $latest;
    }

    private function HandleGetDataLora()
    {
        sleep(3);
        $data = $this->HandleIncludePartOfObjectInsideArray($this->HandleGetDataLoraLatest(
            config('lorawan.url'),
            config('lorawan.endpoint'),
            config('lorawan.token'),
            config('lorawan.accept'),
        ));

        //cek data null or another error
        if (!empty($data) && $data != 0) {
            Log::info('LorawanJob: Success fetch data');
            Log::info($data);
        } else {
            Log::info('LorawanJob: Failed fetch data');
        }
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->HandleGetDataLora();
    }
}
