<?php
// config/services.php
return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK'),

        'guzzle' => [
            'verify' => false, // Nonaktifkan verifikasi SSL (untuk pengembangan lokal)
        ],
    ],
];
