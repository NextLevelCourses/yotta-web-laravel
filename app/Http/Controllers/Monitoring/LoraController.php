<?php

namespace App\Http\Controllers\Monitoring;

use App\Exports\LoraTestExport;
use App\Models\LoRa;
use App\LoraInterface;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class LoraController extends Controller implements LoraInterface
{
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
        // Check if index 9 exists
        if (empty($jsonObject[9])) {
            return false;
        }
        
        // Validate that $jsonObject[9] is an array
        if (!is_array($jsonObject[9])) {
            return false;
        }
        
        // Validate the nested structure exists
        if (!isset($jsonObject[9]['result']['uplink_message']['decoded_payload'])) {
            return false;
        }
        
        // Validate that decoded_payload is an array
        if (!is_array($jsonObject[9]['result']['uplink_message']['decoded_payload'])) {
            return false;
        }
        
        return true;
    }

    public function HandleIncludePartOfObjectInsideArray($raw): array
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            $decoded = json_decode($json, true);
            
            // Validate that json_decode succeeded and returned an array
            if ($decoded === null || !is_array($decoded)) {
                Log::warning('Failed to decode JSON object at index ' . $i);
                return null;
            }
            
            return $decoded;
        }, $jsonObjects, array_keys($jsonObjects));
        
        // Filter out any null values from failed json_decode
        $jsonObjects = array_filter($jsonObjects, function($obj) {
            return $obj !== null;
        });
        
        // Re-index the array after filtering
        $jsonObjects = array_values($jsonObjects);

        //kalo data last(yang paling baru) belum masuk maka ambil data sebelumnya
        if (!$this->HandleValidateExistsDataLora($jsonObjects)) {
            $oldest = [];
            for ($i = 0; $i <= 8; $i++) {
                // Validate that the object exists and has the required structure
                if (!isset($jsonObjects[$i]) || !is_array($jsonObjects[$i])) {
                    Log::warning("Invalid or missing object at index $i");
                    continue;
                }
                
                if (!isset($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])) {
                    Log::warning("Missing decoded_payload at index $i");
                    continue;
                }
                
                if (!is_array($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])) {
                    Log::warning("decoded_payload is not an array at index $i");
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
        // Additional safety check before accessing nested keys
        if (!isset($jsonObjects[9]['result']['uplink_message']['decoded_payload']) || 
            !is_array($jsonObjects[9]['result']['uplink_message']['decoded_payload'])) {
            Log::error('Invalid structure for latest data at index 9');
            throw new \Exception('Invalid LoRa data structure');
        }
        
        $payload = $jsonObjects[9]['result']['uplink_message']['decoded_payload'];
        
        $latest = array(
            'air_humidity' => $payload['air_humidity'] ?? null,
            'air_temperature' => $payload['air_temperature'] ?? null,
            'nitrogen' => $payload['nitrogen'] ?? null,
            'phosphorus' => $payload['phosphorus'] ?? null,
            'potassium' => $payload['potassium'] ?? null,
            'soil_conductivity' => $payload['soil_conductivity'] ?? null,
            'soil_humidity' => $payload['soil_humidity'] ?? null,
            'soil_pH' => $payload['soil_pH'] ?? null,
            'soil_temperature' => $payload['soil_temperature'] ?? null,
            'measured_at' => now()->format('Y-m-d H:i:s')
        );
        LoRa::create($latest);
        return $latest;
    }

    public function HandleGetDataLora()
    {
        if (!$this->HandleValidateDataLoraContentType()) {
            return $this->ResponseError('Content Type Is Null', 422);
        }

        if (!$this->HandleValidateDataLoraEndpoint()) {
            return $this->ResponseError('Endpoint Is Null', 422);
        }

        if (!$this->HandleValidateDataLoraUrl()) {
            return $this->ResponseError('Base Url Is Null', 422);
        }

        if (!$this->HandleValidateDataLoraToken()) {
            return $this->ResponseError('Token Is Null', 422);
        }

        if (
            $this->HandleValidateDataLoraContentType() &&
            $this->HandleValidateDataLoraEndpoint() &&
            $this->HandleValidateDataLoraUrl() &&
            $this->HandleValidateDataLoraToken()
        ) {
            try {
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
                return $this->ResponseOk($this->HandleIncludePartOfObjectInsideArray($raw), 'SuccessFully Store Data Realtime');
            } catch (\Exception $error) {
                Log::error($error->getMessage());
                return $this->ResponseError($error->getMessage(), 500);
            }
        }
    }
    /**
     * Tampilkan halaman monitoring (Blade)
     */

    public function index()
    {
        return view('monitoring.lora');
    }

    public function export(string $date): BinaryFileResponse|string
    {
        try {
            $month = substr($date, 5, 2); // hasilnya mm (month) only without yyyy or dd
            $year = substr($date, 0, 4); // hasilnya yyyy (year) only without mm or dd
            $now = date('Y-m-d');
            return Excel::download(new LoraTestExport($month, $year), "loratest{$now}.xlsx");
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return 'Error exporting data';
        }
    }
}
