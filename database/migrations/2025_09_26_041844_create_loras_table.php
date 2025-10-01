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
        Schema::create('loras', function (Blueprint $table) {
            $table->id();
            $table->string('device_id')->nullable();
            $table->float('air_humidity')->nullable();
            $table->float('air_temperature')->nullable();
            $table->float('nitrogen')->nullable();
            $table->float('phosphorus')->nullable();
            $table->float('potassium')->nullable();
            $table->float('soil_conductivity')->nullable();
            $table->float('soil_humidity')->nullable();
            $table->float('soil_pH')->nullable();
            $table->float('soil_temperature')->nullable();
            $table->timestamp('measured_at')->nullable()->default(now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loras');
    }
};
