<?php

namespace App\Http\Controllers;

use App\LoraInterface;
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

    public function HandleGetDataLoraLatest(
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
