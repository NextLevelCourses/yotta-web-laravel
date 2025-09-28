<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //constanta
    const LATEST = 'desc';
    const OLDEST = 'asc';
    //interface
    abstract public function HandleGetDataGrafikSoilTest(string $param, string $column, string $sort = 'asc'): array;
}
