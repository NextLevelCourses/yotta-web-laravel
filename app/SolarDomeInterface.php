<?php

namespace App;

use Symfony\Component\HttpFoundation\JsonResponse;

interface SolarDomeInterface
{
    public static function HandleGetDataSolarDome($data, int $key): array|JsonResponse;
}
