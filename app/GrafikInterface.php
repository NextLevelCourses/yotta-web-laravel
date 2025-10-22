<?php

namespace App;

interface GrafikInterface
{
    public function HandleGetDataGrafik(string $param, string $column, string $sort = 'asc'): array;
}
