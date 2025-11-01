<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solar_dome extends Model
{
    protected $fillable = [
        'temperature',
        'humidity',
        'controlMode',
        'relayState',
        'targetHumidity',
        'measure_at',
        'created_at',
    ];

    protected $casts = [
        'measure_at' => 'datetime',
    ];
}
