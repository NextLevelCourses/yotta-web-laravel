<?php

namespace App\Exports;

use App\Models\SoilTest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SoilTestExport implements FromCollection, WithHeadings, WithMapping
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
        return SoilTest::whereMonth('measured_at', $this->month)
            ->whereYear('measured_at', $this->year)
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->device_id ?? 'Unknown Device',
            $row->measured_at ?? '-',
            $row->temperature ? $row->temperature  : '0',
            $row->humidity ? $row->humidity : '0',
            $row->ph ? $row->ph : '0',
            $row->ec ? $row->ec : '0',
            $row->nitrogen ? $row->nitrogen : '0',
            $row->fosfor ? $row->fosfor : '0',
            $row->kalium ? $row->kalium : '0',
            $row->status ?? '-'
        ];
    }

    public function headings(): array
    {
        return [
            'Device ID',
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
