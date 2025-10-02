<?php

namespace App\Console\Commands;

use App\Models\LoRa;
use App\LoraInterface;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiLorawanCommand extends Command implements LoraInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lorawan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
                $json_output = json_encode($this->HandleIncludePartOfObjectInsideArray($raw), JSON_PRETTY_PRINT);
                Log::info($json_output);
                $this->info($json_output);
            } catch (\Exception $error) {
                Log::error($error->getMessage());
                $this->info($error->getMessage());
            }
        }
    }
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->HandleGetDataLora();
    }
}
