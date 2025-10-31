<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel stasiun_cuacas.
     */
    public function up(): void
    {
        Schema::create('stasiun_cuacas', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->index();       // ID dari perangkat (Arduino/ESP32)
            $table->float('co2')->nullable();           // ppm
            $table->float('humidity')->nullable();      // %
            $table->time('jam')->nullable();            // waktu pengukuran
            $table->float('nh3')->nullable();           // ppm
            $table->float('no2')->nullable();           // ppm
            $table->float('o3')->nullable();            // ppm
            $table->float('pm10')->nullable();          // µg/m3
            $table->float('pm25')->nullable();          // µg/m3
            $table->float('rainfall')->nullable();      // mm
            $table->float('so2')->nullable();           // ppm
            $table->float('solar_radiation')->nullable(); // W/m²
            $table->date('tanggal')->nullable();        // tanggal pengukuran
            $table->float('temperature')->nullable();   // °C
            $table->float('tvoc')->nullable();          // ppb
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('stasiun_cuacas');
    }
};
