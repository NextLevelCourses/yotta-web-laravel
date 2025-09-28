<?php

namespace App;

interface SoiltestInterface
{
    public function HandleGetDataGrafikSoilTest(string $param, string $column, string $sort = 'asc'): array;
}
