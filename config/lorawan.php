<?php

return [
    "lorawan" => [
        "url" => env('MASS_STORAGE_LORAWAN_URL', 'https://example.com'),
        "token" => env('MASS_STORAGE_LORAWAN_TOKEN', 'token not set'),
        "accept" => env('MASS_STORAGE_LORAWAN_ACCEPT', 'application/json')
    ]
];
