<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pengaturan MQTT Broker
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan server, port, dan kredensial untuk
    | koneksi ke MQTT Broker Anda.
    |
    */

    'server' => env('MQTT_SERVER', 'broker.hivemq.com'),
    'port' => env('MQTT_PORT', 1883),
    'user' => env('MQTT_USER', null),
    'password' => env('MQTT_PASSWORD', null),
];
