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
        // Validate that index 9 exists and has the expected structure
        if (empty($jsonObject[9])) {
            return false;
        }
        
        // Validate the complete nested structure
        return isset($jsonObject[9]['result']['uplink_message']['decoded_payload']);
    }
    
    /**
     * Safely extract decoded payload from a JSON object with proper validation
     * 
     * @param array|null $jsonObject The JSON object to extract from
     * @return array|null The decoded payload or null if not found
     */
    private function safeExtractDecodedPayload($jsonObject): ?array
    {
        if (!isset($jsonObject['result']['uplink_message']['decoded_payload'])) {
            Log::warning('Unexpected LoRa response structure', [
                'object_keys' => $jsonObject ? array_keys($jsonObject) : null
            ]);
            return null;
        }
        
        return $jsonObject['result']['uplink_message']['decoded_payload'];
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
            Log::info('Latest LoRa data (index 9) not available or has unexpected structure, processing older data', [
                'object_count' => count($jsonObjects)
            ]);
            
            $oldest = [];
            for ($i = 0; $i <= 8; $i++) {
                // Check if the index exists
                if (!isset($jsonObjects[$i])) {
                    Log::warning("LoRa data at index {$i} does not exist", [
                        'available_indices' => array_keys($jsonObjects)
                    ]);
                    continue;
                }
                
                // Safely extract the decoded payload
                $decodedPayload = $this->safeExtractDecodedPayload($jsonObjects[$i]);
                if ($decodedPayload === null) {
                    Log::warning("Failed to extract decoded payload at index {$i}");
                    continue;
                }
                
                array_push($oldest, $decodedPayload);
            }
            
            if (!empty($oldest)) {
                DB::table('loras')->insert($oldest);
            }
            return $oldest;
        }

        //ambil last data(data paling update)
        $decodedPayload = $this->safeExtractDecodedPayload($jsonObjects[9]);
        
        if ($decodedPayload === null) {
            Log::error('Failed to extract decoded payload from latest LoRa data at index 9');
            throw new \RuntimeException('Invalid LoRa response structure: missing decoded_payload');
        }
        
        $latest = array(
            'air_humidity' => $decodedPayload['air_humidity'] ?? null,
            'air_temperature' => $decodedPayload['air_temperature'] ?? null,
            'nitrogen' => $decodedPayload['nitrogen'] ?? null,
            'phosphorus' => $decodedPayload['phosphorus'] ?? null,
            'potassium' => $decodedPayload['potassium'] ?? null,
            'soil_conductivity' => $decodedPayload['soil_conductivity'] ?? null,
            'soil_humidity' => $decodedPayload['soil_humidity'] ?? null,
            'soil_pH' => $decodedPayload['soil_pH'] ?? null,
            'soil_temperature' => $decodedPayload['soil_temperature'] ?? null,
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
