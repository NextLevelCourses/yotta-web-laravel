<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StasiunCuaca extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',     // arduino devices
        'co2',
        'humidity',      // kelembaban tanah
        'jam',
        'nh3',
        'no2',
        'o3',
        'pm10',
        'pm25',
        'rainfall',
        'so2',
        'solar_radiation',
        'tanggal',
        'temperature',   // suhu tanah
        'tvoc',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
