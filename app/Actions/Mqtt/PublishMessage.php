<?php

namespace App\Actions\Mqtt;

// PASTIKAN ANDA MENAMBAHKAN TIGA BARIS INI
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use Illuminate\Support\Facades\Log;

class PublishMessage
{
    /**
     * Menangani pengiriman pesan ke MQTT Broker.
     *
     * @param string $topic
     * @param string $message
     * @param int $qualityOfService
     * @return bool
     */
    public static function handle(string $topic, string $message, int $qualityOfService = 1): bool
    {
        // Ambil konfigurasi dari file config/mqtt.php
        $server   = config('mqtt.server', 'broker.hivemq.com');
        $port     = config('mqtt.port', 1883);
        $clientId = 'laravel-mqtt-client-' . uniqid();

        try {
            $mqtt = new MqttClient($server, $port, $clientId);
            $mqtt->connect();
            $mqtt->publish($topic, $message, $qualityOfService);
            $mqtt->disconnect();

            return true;

        } catch (MqttClientException $e) {
            // Catat error ke log untuk debugging
            Log::error("Gagal mengirim pesan MQTT ke topic '{$topic}': " . $e->getMessage());
            return false;
        }
    }
}
