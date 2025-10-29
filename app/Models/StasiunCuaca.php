<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StasiunCuaca extends Model
{
    use HasFactory;

    protected $table = 'stasiun_cuacas';

    protected $fillable = [
        'device_id',
        'gps_latitude',
        'gps_longitude',
        'gps_altitude',
        'gps_speed_kmh',
        'gps_satellites',
        'gps_hdop',
        'gps_local_time',
        'temperature',
        'humidity',
        'solar_radiation',
        'rainfall',
        'co2',
        'tvoc',
        'nh3',
        'no2',
        'o3',
        'so2',
        'pm10',
        'pm25',
    ];
}
