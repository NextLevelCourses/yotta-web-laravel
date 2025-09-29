<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\LoRa;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe ke broker MQTT TTN dan simpan data ke database';

    public function handle()
    {
        $server   = 'au1.cloud.thethings.network';
        $port     = 1883;
        $clientId = 'laravel-client-' . uniqid();

        // Username = Application ID dari TTN
        $username = 'test101a@ttn';
        // Password = API Key dari TTN
        $password = 'NNSXS.JGJGATTX2DUG7RAAJS7O5BJNPQJF2IKCH4QIQYI.PH4WEUHY7SEVU7AEDASWFB2AKFROGBXXEVQBS6X6IVYPNNZGXTBA'; // ganti dengan API key asli

        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId);

        $mqtt->connect($connectionSettings, true);

        $this->info("✅ Terhubung ke MQTT broker: {$server}:{$port}");

        // Subscribe ke topik TTN
        $topic = 'v3/test101a@ttn/devices/+/up'; // format TTN untuk uplink
        $mqtt->subscribe($topic, function (string $topic, string $message) {
            $this->info("📩 Pesan diterima dari {$topic}: {$message}");

            $data = json_decode($message, true);

            if ($data && isset($data['uplink_message']['decoded_payload'])) {
                $payload = $data['uplink_message']['decoded_payload'];

                LoRa::create([
                    'air_humidity'      => $payload['air_humidity'] ?? null,
                    'air_temperature'   => $payload['air_temperature'] ?? null,
                    'nitrogen'          => $payload['nitrogen'] ?? null,
                    'phosphorus'        => $payload['phosphorus'] ?? null,
                    'potassium'         => $payload['potassium'] ?? null,
                    'soil_conductivity' => $payload['soil_conductivity'] ?? null,
                    'soil_humidity'     => $payload['soil_humidity'] ?? null,
                    'soil_pH'           => $payload['soil_pH'] ?? null,
                    'soil_temperature'  => $payload['soil_temperature'] ?? null,
                ]);

                $this->info("💾 Data LoRa berhasil disimpan ke database.");
            }
        }, 0);

        $mqtt->loop(true);
    }
}
