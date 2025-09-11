<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soil_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->float('temperature')->nullable();  // suhu tanah
            $table->float('humidity')->nullable();     // kelembaban
            $table->float('ph')->nullable();           // pH
            $table->float('ec')->nullable();           // electrical conductivity
            $table->string('status')->nullable();      // status tanah
            $table->timestamp('measured_at')->nullable(); // waktu pengukuran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soil_tests');
    }
};
