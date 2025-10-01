<?php

namespace App\Exports;

use App\Models\LoRa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoraTestExport implements FromCollection, WithHeadings, WithMapping
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
        return LoRa::whereMonth('measured_at', $this->month)
            ->whereYear('measured_at', $this->year)
            ->get();
    }

    public function map($row): array
    {
        return [
            $row->device_id ?? 'Unknown Device',
            $row->measured_at ?? '-',
            $row->air_humidity ? $row->air_humidity  : '0',
            $row->air_temperature ? $row->air_temperature : '0',
            $row->nitrogen ? $row->nitrogen : '0',
            $row->phosphorus ? $row->phosphorus : '0',
            $row->potassium ? $row->potassium : '0',
            $row->soil_conductivity ? $row->soil_conductivity : '0',
            $row->soil_humidity ? $row->soil_humidity : '0',
            $row->soil_pH ? $row->soil_pH : '0',
            $row->soil_temperature ? $row->soil_temperature : '0',
        ];
    }

    public function headings(): array
    {
        return [
            'Device ID',
            'Measured At',
            'Air Humidity (%)',
            'Air Temperature (°C)',
            'Nitrogen',
            'Phosphorus',
            'Potassium',
            'Soil Conductivity (mS/cm)',
            'Soil Humidity (%)',
            'Soil pH',
            'Soil Temp (°C)',
        ];
    }
}
