<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stasiun_cuacas', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable(); // "Stasiun Cuaca YAE01"

            // Data GPS
            $table->float('gps_latitude', 10, 6)->nullable();
            $table->float('gps_longitude', 10, 6)->nullable();
            $table->float('gps_altitude', 8, 2)->nullable();
            $table->float('gps_speed_kmh', 8, 2)->nullable();
            $table->integer('gps_satellites')->nullable();
            $table->float('gps_hdop', 8, 2)->nullable();
            $table->string('gps_local_time')->nullable();

            // Data lingkungan
            $table->float('temperature', 8, 2)->nullable();
            $table->float('humidity', 8, 2)->nullable();
            $table->float('solar_radiation', 8, 2)->nullable();
            $table->float('rainfall', 8, 2)->nullable();

            // Gas sensor
            $table->float('co2', 8, 2)->nullable();
            $table->float('tvoc', 8, 2)->nullable();
            $table->float('nh3', 8, 2)->nullable();
            $table->float('no2', 8, 2)->nullable();
            $table->float('o3', 8, 2)->nullable();
            $table->float('so2', 8, 2)->nullable();

            // Particulate Matter
            $table->float('pm10', 8, 2)->nullable();
            $table->float('pm25', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stasiun_cuacas');
    }
};
