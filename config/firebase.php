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

    'credentials' => base_path(env('FIREBASE_CREDENTIALS')),

    /*
    |--------------------------------------------------------------------------
    | Database (Firestore)
    |--------------------------------------------------------------------------
    */
    'database' => [
        'default' => env('FIREBASE_DATABASE_URL', null),
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
