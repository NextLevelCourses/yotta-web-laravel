<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\LoraInterface;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        if (!$this->HandleValidateExistsDataLora($jsonObjects)) {
            return array();
        }

        $result = [];
        for ($i = 0; $i <= 8; $i++) {
            // array_push($result, array('f_cnt' => $jsonObjects[$i]['result']['uplink_message']['f_cnt']));
            array_push($result, $jsonObjects[$i]['result']['uplink_message']);
        }
        return $result;
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
                return $this->ResponseOk($this->HandleIncludePartOfObjectInsideArray($raw), 'SuccessFully');
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
}
