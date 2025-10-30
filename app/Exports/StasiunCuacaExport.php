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
        return StasiunCuaca::whereMonth('measured_at', $this->month)
            ->whereYear('measured_at', $this->year)
            ->get();
    }

    /**
     * Tentukan urutan dan isi kolom di Excel.
     */
    public function map($data): array
    {
        return [
            $data->id,
            $data->device_id,
            $data->tanggal,
            $data->jam,
            $data->temperature,
            $data->humidity,
            $data->rainfall,
            $data->solar_radiation,
            $data->co2,
            $data->nh3,
            $data->no2,
            $data->o3,
            $data->pm10,
            $data->pm25,
            $data->so2,
            $data->tvoc,
        ];
    }

    /**
     * Header kolom untuk file Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Device ID',
            'Tanggal',
            'Jam',
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
