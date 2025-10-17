<?php

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;

interface LoraInterface
{
    public function HandleValidateDataLoraToken(): bool;
    public function HandleValidateDataLoraUrl(): bool;
    public function HandleValidateDataLoraEndpoint(): bool;
    public function HandleValidateDataLoraContentType(): bool;
    public function HandleGetDataLoraLatest(string $url, string $endpoint, string $authorization, string $accept, string $lastData = '1h');
}
