<?php

namespace App\Exports;

use App\Models\Solar_dome;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class SolarDomeExport implements FromCollection, WithHeadings, WithMapping
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

    /**
     * Ambil semua data Stasiun Cuaca dari database.
     */
    public function collection()
    {
        return Solar_dome::whereMonth('measure_at', $this->month)
            ->whereYear('measure_at', $this->year)
            ->orderByDesc('id')
            ->get();
    }

    /**
     * Tentukan urutan dan isi kolom di Excel.
     */
    public function map($data): array
    {
        return [
            $data->measure_at ? $data->measure_at : '0000-00-00',
            $data->temperature ? $data->temperature : '0',
            $data->humidity ? $data->humidity : '0',
            $data->controlMode ? $data->controlMode : '-',
            $data->relayState ? 'ON' : 'OFF',
            $data->targetHumidity ? $data->targetHumidity : '0',
        ];
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'Measure At',
            'Temperature (°C)',
            'Humidity (%)',
            'Control Mode',
            'Relay State',
            'Target Humidity (%)',
        ];
    }
}
