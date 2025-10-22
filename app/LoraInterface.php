<?php

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;

interface LoraInterface
{
    public function HandleLoraValidateDataToken(): bool;
    public function HandleLoraValidateDataUrl(): bool;
    public function HandleLoraValidateDataEndpoint(): bool;
    public function HandleLoraValidateDataContentType(): bool;
    public function HandleLoraIncludePartOfObjectInsideArray($raw): array|string|int;
    public function HandleLoraGetApi(string $url, string $endpoint, string $authorization, string $accept, string $lastData = '1h');
}
