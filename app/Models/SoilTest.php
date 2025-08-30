<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoilTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'temperature',   // suhu tanah
        'humidity',      // kelembaban tanah
        'ph',            // tingkat keasaman
        'ec',            // electrical conductivity
        'status',        // status kondisi tanah
        'measured_at',   // waktu pengukuran
    ];

    protected $casts = [
        'measured_at' => 'datetime',
    ];
}
