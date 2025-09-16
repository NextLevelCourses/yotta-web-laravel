<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoilTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',     // arduino devices
        'temperature',   // suhu tanah
        'humidity',      // kelembaban tanah
        'ph',            // tingkat keasaman
        'ec',            // electrical conductivity
        'nitrogen',      // nitrogen
        'fosfor',        // fosfor
        'kalium',        // kalium
        'status',        // status kondisi tanah
        'measured_at',   // waktu pengukuran
    ];

    protected $casts = [
        'measured_at' => 'datetime',
    ];
}
