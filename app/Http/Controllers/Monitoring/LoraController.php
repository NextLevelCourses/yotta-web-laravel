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
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //kalo data last(yang paling baru) belum masuk maka ambil data sebelumnya
        if (!$this->HandleValidateExistsDataLora($jsonObjects)) {
            $oldest = [];
            for ($i = 0; $i <= 8; $i++) {
                // Check if the array structure exists before accessing nested keys
                if (
                    isset($jsonObjects[$i]) &&
                    isset($jsonObjects[$i]['result']) &&
                    isset($jsonObjects[$i]['result']['uplink_message']) &&
                    isset($jsonObjects[$i]['result']['uplink_message']['decoded_payload'])
                ) {
                    array_push($oldest, $jsonObjects[$i]['result']['uplink_message']['decoded_payload']);
                }
            }
            if (!empty($oldest)) {
                DB::table('loras')->insert($oldest);
            }
            return $oldest;
        }

        // Check if the required structure exists for the latest data
        if (
            !isset($jsonObjects[9]) ||
            !isset($jsonObjects[9]['result']) ||
            !isset($jsonObjects[9]['result']['uplink_message']) ||
            !isset($jsonObjects[9]['result']['uplink_message']['decoded_payload'])
        ) {
            Log::error('Invalid data structure received from LoRa API');
            return [];
        }

        $decodedPayload = $jsonObjects[9]['result']['uplink_message']['decoded_payload'];

        // Verify all required fields exist in the decoded payload
        $requiredFields = [
            'air_humidity', 'air_temperature', 'nitrogen', 'phosphorus',
            'potassium', 'soil_conductivity', 'soil_humidity', 'soil_pH', 'soil_temperature'
        ];

        foreach ($requiredFields as $field) {
            if (!isset($decodedPayload[$field])) {
                Log::error("Missing required field: {$field} in LoRa API response");
                return [];
            }
        }

        //ambil last data(data paling update)
        $latest = array(
            'air_humidity' => $decodedPayload['air_humidity'],
            'air_temperature' => $decodedPayload['air_temperature'],
            'nitrogen' => $decodedPayload['nitrogen'],
            'phosphorus' => $decodedPayload['phosphorus'],
            'potassium' => $decodedPayload['potassium'],
            'soil_conductivity' => $decodedPayload['soil_conductivity'],
            'soil_humidity' => $decodedPayload['soil_humidity'],
            'soil_pH' => $decodedPayload['soil_pH'],
            'soil_temperature' => $decodedPayload['soil_temperature'],
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
