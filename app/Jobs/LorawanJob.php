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
        // Validate input
        if (empty($raw) || !is_string($raw)) {
            Log::error("Invalid input to HandleIncludePartOfObjectInsideArray");
            return [];
        }
        
        $jsonObjects = preg_split('/}\s*{/', $raw);
        
        // Validate preg_split result
        if ($jsonObjects === false || empty($jsonObjects)) {
            Log::error("Failed to split JSON objects");
            return [];
        }
        
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
                // Check if the index exists and has the required structure
                if (!isset($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])) {
                    Log::error("Missing required data structure at index $i");
                    continue;
                }
                array_push($oldest, $jsonObjects[$i]['result']['uplink_message']['decoded_payload']);
            }
            
            if (!empty($oldest)) {
                DB::table('loras')->insert($oldest);
            }
            return $oldest;
        }

        // Validate that index 9 exists and has required structure before accessing
        if (!isset($jsonObjects[9]['result']['uplink_message']['decoded_payload'])) {
            Log::error("Missing required data structure at index 9");
            return [];
        }
        
        $payload = $jsonObjects[9]['result']['uplink_message']['decoded_payload'];
        
        // Validate all required fields exist
        $requiredFields = [
            'air_humidity', 'air_temperature', 'nitrogen', 'phosphorus', 
            'potassium', 'soil_conductivity', 'soil_humidity', 'soil_pH', 
            'soil_temperature'
        ];
        
        foreach ($requiredFields as $field) {
            if (!isset($payload[$field])) {
                Log::error("Missing required field: $field in payload");
                return [];
            }
        }
        
        //ambil last data(data paling update)
        $latest = array(
            'air_humidity' => $payload['air_humidity'],
            'air_temperature' => $payload['air_temperature'],
            'nitrogen' => $payload['nitrogen'],
            'phosphorus' => $payload['phosphorus'],
            'potassium' => $payload['potassium'],
            'soil_conductivity' => $payload['soil_conductivity'],
            'soil_humidity' => $payload['soil_humidity'],
            'soil_pH' => $payload['soil_pH'],
            'soil_temperature' => $payload['soil_temperature'],
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
                
                // Validate response before processing
                if (empty($raw)) {
                    Log::error("Empty response received from LoRaWAN API");
                    return;
                }
                
                $json_output = json_encode($this->HandleIncludePartOfObjectInsideArray($raw), JSON_PRETTY_PRINT);
                Log::info("Berhasil get data dari lorawan");
                Log::info($json_output);
            } catch (\Exception $error) {
                Log::error($error->getMessage());
                return; // Stop execution when API call fails
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
