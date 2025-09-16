<?php

namespace App\Exports;

use App\Models\SoilTest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SoilTestExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        $data = SoilTest::all()->map(function ($item) {
            return [
                'Measured At' => $item->measured_at,
                'Temperature' => $item->temperature,
                'Humidity' => $item->humidity,
                'pH' => $item->ph,
                'EC' => $item->ec,
                'Nitrogen' => $item->nitrogen,
                'Fosfor' => $item->fosfor,
                'Status' => $item->status,
            ];
        });
        return $data;
    }

    public function headings(): array
    {
        return [
            'Measured At',
            'Temperature',
            'Humidity',
            'pH',
            'EC',
            'Nitrogen',
            'Fosfor',
            'Status',
        ];
    }
}
