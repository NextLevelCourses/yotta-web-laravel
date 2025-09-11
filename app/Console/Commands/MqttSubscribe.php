<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use App\Models\AirQuality;
use Illuminate\Support\Facades\Log;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe to an MQTT topic and save data to the database';

    public function handle()
    {
        $server   = 'broker.emqx.io';
        $port     = 1883;
        $clientId = 'laravel-subscriber-' . uniqid();
        $topic    = 'tes/topic/sensor'; // HARUS sama dengan Arduino

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect();
        $this->info("✅ Connected to MQTT Broker: {$server}");

        $mqtt->subscribe($topic, function (string $topic, string $message) {
            $this->info("📩 Received on [{$topic}]: {$message}");

            // Decode pesan JSON dari Arduino
            $data = json_decode($message, true);

            if (is_array($data) && isset($data['temperature']) && isset($data['humidity'])) {
                AirQuality::create([
                    'temperature' => $data['temperature'],
                    'humidity'    => $data['humidity'],
                    'air_quality' => $data['air_quality'] ?? 0, // default 0 jika tidak ada
                ]);
                $this->info("💾 Data saved: Temp={$data['temperature']}, Hum={$data['humidity']}");
            } else {
                Log::warning("⚠️ Invalid MQTT payload: {$message}");
            }
        }, 0);

        $this->info("📡 Subscribed to topic: {$topic}");
        $mqtt->loop(true); // Keep listening
    }
}
