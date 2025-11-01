<?php

namespace App\Exports;

use App\Models\StasiunCuaca;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StasiunCuacaExport implements FromCollection, WithHeadings, WithMapping
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
        return StasiunCuaca::whereMonth('tanggal', $this->month)
            ->whereYear('tanggal', $this->year)
            ->orderByDesc('tanggal')
            ->orderByDesc('jam')
            ->get();
    }

    /**
     * Tentukan urutan dan isi kolom di Excel.
     */
    public function map($data): array
    {
        return [
            $data->tanggal ?? '0000-00-00',
            $data->jam ?? '00:00',
            $data->device_id ?? 'Unknown Device',
            $data->temperature ? $data->temperature : '0',
            $data->humidity ? $data->humidity : '0',
            $data->rainfall ? $data->rainfall : '0',
            $data->solar_radiation ? $data->solar_radiation : '0',
            $data->co2 ? $data->co2 : '0',
            $data->nh3 ? $data->nh3 : '0',
            $data->no2 ? $data->no2 : '0',
            $data->o3 ? $data->o3 : '0',
            $data->pm10 ? $data->pm10 : '0',
            $data->pm25 ? $data->pm25 : '0',
            $data->so2 ? $data->so2 : '0',
            $data->tvoc ? $data->tvoc : '0',
        ];
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam',
            'Device ID',
            'Temperature (°C)',
            'Humidity (%)',
            'Rainfall (mm)',
            'Solar Radiation (W/m²)',
            'CO₂ (ppm)',
            'NH₃ (ppm)',
            'NO₂ (ppm)',
            'O₃ (ppm)',
            'PM10 (µg/m³)',
            'PM2.5 (µg/m³)',
            'SO₂ (ppm)',
            'TVOC (ppb)',
        ];
    }
}
