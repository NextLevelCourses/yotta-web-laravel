<?php

namespace App\Jobs;

use App\LoraInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\LoRa;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LorawanJob implements ShouldQueue, LoraInterface
{
    use Queueable;

    public function HandleValidateDataLoraContentType(): bool
    {
        return !empty(config('lorawan.accept')) ? true : false;
    }

    public function HandleValidateDataLoraEndpoint(): bool
    {
        return !empty(config('lorawan.endpoint')) ? true : false;
    }

    public function HandleValidateDataLoraUrl(): bool
    {
        return !empty(config('lorawan.url')) ? true : false;
    }

    public function HandleValidateDataLoraToken(): bool
    {
        return !empty(config('lorawan.token')) ? true : false;
    }

    public function HandleValidateExistsDataLora($jsonObject): bool
    {
        return empty($jsonObject[9]) ? false : true; //targetnya: index terakhir untuk ngambil data paling update
    }

    public function HandleIncludePartOfObjectInsideArray($raw): array
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //kalo data last(yang paling baru) belum masuk maka ambil data sebelumnya
        if (!$this->HandleValidateExistsDataLora($jsonObjects)) {
            $oldest = [];
            for ($i = 0; $i <= 8; $i++) {
                array_push($oldest, $jsonObjects[$i]['result']['uplink_message']['decoded_payload']);
            }
            DB::table('loras')->insert($oldest);
            return $oldest;
        }

        //ambil last data(data paling update)
        $latest = array(
            'air_humidity' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['air_humidity'],
            'air_temperature' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['air_temperature'],
            'nitrogen' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['nitrogen'],
            'phosphorus' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['phosphorus'],
            'potassium' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['potassium'],
            'soil_conductivity' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['soil_conductivity'],
            'soil_humidity' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['soil_humidity'],
            'soil_pH' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['soil_pH'],
            'soil_temperature' => $jsonObjects[9]['result']['uplink_message']['decoded_payload']['soil_temperature'],
            'measured_at' => now()->format('Y-m-d H:i:s')
        );
        LoRa::create($latest);
        return $latest;
    }

    public function HandleGetDataLora()
    {
        if (!$this->HandleValidateDataLoraContentType()) {
            Log::info('Content Type Is Null');
        }

        if (!$this->HandleValidateDataLoraEndpoint()) {
            Log::info('Endpoint Is Null');
        }

        if (!$this->HandleValidateDataLoraUrl()) {
            Log::info('Base Url Is Null');
        }

        if (!$this->HandleValidateDataLoraToken()) {
            Log::info('Token Is Null');
        }

        if (
            $this->HandleValidateDataLoraContentType() &&
            $this->HandleValidateDataLoraEndpoint() &&
            $this->HandleValidateDataLoraUrl() &&
            $this->HandleValidateDataLoraToken()
        ) {
            try {
                sleep(3); //hold 3 second biar servernya bisa nafas bentar baru jalan lagi
                $client = new Client(['base_uri' => config('lorawan.url')]);
                $response = $client->request('GET', config('lorawan.endpoint'), [
                    'headers' => [
                        'Authorization' => config('lorawan.token'),
                        'Accept'        => config('lorawan.accept'),
                    ],
                    'query' => [
                        'last' => '1h'
                    ]
                ]);
                $raw = $response->getBody()->getContents();
                $json_output = json_encode($this->HandleIncludePartOfObjectInsideArray($raw), JSON_PRETTY_PRINT);
                Log::info("Berhasil get data dari lorawan");
                Log::info($json_output);
            } catch (\Exception $error) {
                Log::error($error->getMessage());
            }
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
