<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LoraInterface;
use App\Models\LoRa;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class Controller implements LoraInterface
{
    //constanta
    const LATEST = 'desc';
    const OLDEST = 'asc';

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

    public function HandleIncludePartOfObjectInsideArray($raw): array|string|int
    {
        $jsonObjects = preg_split('/}\s*{/', $raw);
        $jsonObjects = array_map(function ($json, $i) use ($jsonObjects) {
            // Re-add curly braces we removed
            if ($i > 0) $json = '{' . $json;
            if ($i < count($jsonObjects) - 1) $json .= '}';
            return json_decode($json, true);
        }, $jsonObjects, array_keys($jsonObjects));

        //ambil semua data
        $data = [];
        foreach ($jsonObjects as $value) {
            //validate data lora nya exists or null (note: pastikan data dalam bentuk array)
            if (
                !empty($value) &&
                is_array($value)
            ) {
                if (
                    Carbon::parse($value['result']['uplink_message']['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H') >=
                    Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H')
                ) {
                    array_push($data, $value['result']['uplink_message']);
                }
            } else {
                return 0;
            }
        }

        // //map data latest(data paling terbaru saja) supaya realtime alias datanya sesuai dengan apa yang ada di thingsboard
        $latest = array(
            'air_humidity' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['air_humidity'] ?? 0,
            'air_temperature' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['air_temperature'] ?? 0,
            'nitrogen' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['nitrogen'] ?? 0,
            'par_value' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['par_value'] ?? 0,
            'phosphorus' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['phosphorus'] ?? 0,
            'potassium' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['potassium'] ?? 0,
            'soil_conductivity' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['soil_conductivity'] ?? 0,
            'soil_humidity' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['soil_humidity'] ?? 0,
            'soil_pH' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['soil_pH'] ?? 0,
            'soil_temperature' => collect($data)->sortByDesc('f_cnt')->first()['decoded_payload']['soil_temperature'] ?? 0,
            'measured_at' => Carbon::parse(collect($data)->sortByDesc('f_cnt')->first()['received_at'])->timezone(config('app.timezone'))->format('Y-m-d H:i:s') ?? '-',
        );

        //store data latest
        Lora::create($latest);

        //return data
        Log::info(collect($data)->sortByDesc('f_cnt')->first());
        return collect($data)->sortByDesc('f_cnt')->first();
    }

    public function HandleGetApiLora(
        string $url,
        string $endpoint,
        string $authorization,
        string $accept,
        string $lastData = '1h'
    ) {
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
                $client = new Client(['base_uri' => $url]);
                $response = $client->request('GET', $endpoint, [
                    'headers' => [
                        'Authorization' => $authorization,
                        'Accept'        => $accept,
                    ],
                    'query' => [
                        'last' => $lastData
                    ]
                ]);
                return $response->getBody()->getContents();
            } catch (\Exception $error) {
                Log::error($error->getMessage());
                return $this->ResponseError($error->getMessage(), 500);
            }
        }
    }

    protected function ResponseOk($data, string $message = 'no message', int $statusCode = 200)
    {
        return response()->json([
            'status' => 'Successfully',
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function ResponseError(string $message = 'no message', int $statusCode = 422)
    {
        return response()->json([
            'status' => 'Error',
            'code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
