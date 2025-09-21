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

    protected $month, $year;
    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $data = SoilTest::whereMonth('measured_at', $this->month)
            ->whereYear('measured_at', $this->year)
            ->get()
            ->map(function ($item) {
                return [
                    'Measured At' => $item->measured_at ?? '-',
                    'Temperature (°C)' => $item->temperature ?? 0,
                    'Humidity (%)' => $item->humidity ?? 0,
                    'pH' => $item->ph ?? 0,
                    'EC (µS/cm)' => $item->ec ?? 0,
                    'Nitrogen (mg/kg)' => $item->nitrogen ?? 0,
                    'Fosfor (mg/kg)' => $item->fosfor ?? 0,
                    'kalium (mg/kg)' => $item->kalium ?? 0,
                    'Status' => $item->status ?? '-',
                ];
            });
        return $data;
    }

    public function headings(): array
    {
        return [
            'Measured At',
            'Temperature (°C)',
            'Humidity (%)',
            'pH',
            'EC (µS/cm)',
            'Nitrogen (mg/kg)',
            'Fosfor (mg/kg)',
            'kalium (mg/kg)',
            'Status',
        ];
    }
}
