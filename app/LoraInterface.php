<?php

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;

interface LoraInterface
{
    public function HandleValidateDataLoraToken(): bool;
    public function HandleValidateDataLoraUrl(): bool;
    public function HandleValidateDataLoraEndpoint(): bool;
    public function HandleValidateDataLoraContentType(): bool;
    public function HandleIncludePartOfObjectInsideArray($raw): array|string;
    public function HandleValidateExistsDataLora($jsonObject): bool;
    public function HandleGetDataLora();
}
