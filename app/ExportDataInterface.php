<?php

namespace App;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ExportDataInterface
{
    public function ExportByExcel(string $date): BinaryFileResponse|string;
}
