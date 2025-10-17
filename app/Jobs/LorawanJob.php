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
        // Validate that jsonObject is an array and has at least 10 elements
        if (!is_array($jsonObject) || count($jsonObject) < 10) {
            return false;
        }
        
        // Validate that the 10th element exists and has the required structure
        if (empty($jsonObject[9]) || !is_array($jsonObject[9])) {
            return false;
        }
        
        return isset($jsonObject[9]['result']['uplink_message']['decoded_payload']);
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
                // Validate structure before accessing nested keys
                if (!isset($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])) {
                    Log::warning("Invalid data structure at index {$i}, skipping...");
                    continue;
                }
                array_push($oldest, $jsonObjects[$i]['result']['uplink_message']['decoded_payload']);
            }
            
            if (!empty($oldest)) {
                DB::table('loras')->insert($oldest);
            }
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
                $client = new Client([
                    'base_uri' => config('lorawan.url'),
                    'http_errors' => false // Don't throw exceptions on HTTP errors, handle them manually
                ]);
                $response = $client->request('GET', config('lorawan.endpoint'), [
                    'headers' => [
                        'Authorization' => config('lorawan.token'),
                        'Accept'        => config('lorawan.accept'),
                    ],
                    'query' => [
                        'last' => '1h'
                    ]
                ]);
                
                // Check HTTP status code before processing
                $statusCode = $response->getStatusCode();
                
                if ($statusCode === 429) {
                    // Rate limit exceeded
                    $errorBody = $response->getBody()->getContents();
                    Log::warning('LoRaWAN API rate limit exceeded (429 Too Many Requests). Skipping this execution.');
                    Log::warning('Response: ' . $errorBody);
                    return;
                } elseif ($statusCode < 200 || $statusCode >= 300) {
                    // Other HTTP error
                    $errorBody = $response->getBody()->getContents();
                    Log::error("LoRaWAN API returned error status {$statusCode}");
                    Log::error('Response: ' . $errorBody);
                    return;
                }
                
                // Success - process the response
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
