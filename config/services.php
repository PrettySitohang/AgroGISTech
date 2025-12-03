<?php
// config/services.php
return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK', 'http://localhost:8000/auth/google/callback'),
    ],
];
