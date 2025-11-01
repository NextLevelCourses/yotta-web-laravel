<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Di sini kita menggunakan helper `base_path()` dari Laravel untuk
    | membuat path absolut yang benar ke file credentials Anda.
    |
    | Path file itu sendiri diambil dari file .env Anda (variabel FIREBASE_CREDENTIALS).
    | Ini adalah cara yang paling aman dan direkomendasikan.
    |
    */

    'credentials' => [
        'wheater_station' => base_path(env('FIREBASE_CREDENTIALS_WHEATER_STATION')),
        'solar_dome' => base_path(env('FIREBASE_CREDENTIALS_SOLAR_DOME'))
    ],

    /*
    |--------------------------------------------------------------------------
    | Database (Firestore)
    |--------------------------------------------------------------------------
    */
    'database' => [
        'wheater_station' => env('FIREBASE_DATABASE_COLLECTION_WHEATER_STATION', null),
        'solar_dome' => env('FIREBASE_DATABASE_URL_WHEATER_STATION', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage (opsional kalau pakai Firebase Storage)
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'default' => env('FIREBASE_STORAGE_BUCKET', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL', null),
    ],

];
