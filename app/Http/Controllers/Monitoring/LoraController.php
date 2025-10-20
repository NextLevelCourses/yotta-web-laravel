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
        return empty($jsonObject[9]) ? false : true; //targetnya: index terakhir untuk ngambil data paling update
    }

    public function HandleIncludePartOfObjectInsideArray($raw): array
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            $decoded = json_decode($json, true);
            // Return empty array if json_decode fails
            return is_array($decoded) ? $decoded : [];
        }, $jsonObjects, array_keys($jsonObjects));

        //kalo data last(yang paling baru) belum masuk maka ambil data sebelumnya
        if (!$this->HandleValidateExistsDataLora($jsonObjects)) {
            $oldest = [];
            for ($i = 0; $i <= 8; $i++) {
                // Add defensive checks for nested array structure
                if (isset($jsonObjects[$i]['result']['uplink_message']['decoded_payload']) && 
                    is_array($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])) {
                    array_push($oldest, $jsonObjects[$i]['result']['uplink_message']['decoded_payload']);
                }
            }
            if (!empty($oldest)) {
                DB::table('loras')->insert($oldest);
            }
            return $oldest;
        }

        // Validate that index 9 exists and has the expected structure
        if (!isset($jsonObjects[9]['result']['uplink_message']['decoded_payload']) || 
            !is_array($jsonObjects[9]['result']['uplink_message']['decoded_payload'])) {
            Log::warning('LoRa API response does not have expected structure at index 9');
            return [];
        }

        $decodedPayload = $jsonObjects[9]['result']['uplink_message']['decoded_payload'];

        //ambil last data(data paling update)
        $latest = array(
            'air_humidity' => $decodedPayload['air_humidity'] ?? 0,
            'air_temperature' => $decodedPayload['air_temperature'] ?? 0,
            'nitrogen' => $decodedPayload['nitrogen'] ?? 0,
            'phosphorus' => $decodedPayload['phosphorus'] ?? 0,
            'potassium' => $decodedPayload['potassium'] ?? 0,
            'soil_conductivity' => $decodedPayload['soil_conductivity'] ?? 0,
            'soil_humidity' => $decodedPayload['soil_humidity'] ?? 0,
            'soil_pH' => $decodedPayload['soil_pH'] ?? 0,
            'soil_temperature' => $decodedPayload['soil_temperature'] ?? 0,
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
