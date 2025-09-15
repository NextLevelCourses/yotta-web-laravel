<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('air_qualities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable(); // kalau ada relasi device
            $table->float('temperature')->nullable();  // suhu (°C)
            $table->float('humidity')->nullable();     // kelembaban (%)
            $table->integer('air_quality')->default(0); // misalnya PM2.5 / AQI
            $table->enum('fan_status', ['on', 'off'])->default('off'); // status kipas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_qualities');
    }
};
