<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoRa extends Model
{
    protected $table = 'loras';

    protected $fillable = [
        'device_id',
        'air_humidity',
        'air_temperature',
        'nitrogen',
        'phosphorus',
        'potassium',
        'soil_conductivity',
        'soil_humidity',
        'soil_pH',
        'soil_temperature',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
    ];
}
