<?php

return [
    'disk' => env('STORAGE_DRIVER', 'local'),

    'max_file_size' => env('STORAGE_MAX_FILE_SIZE', 1024 * 1024 * 10), // 10MB

    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'application/pdf',
    ],

    'hash_algorithm' => [
        'enabled' => env('STORAGE_HASH_ENABLED', true),
        'algorithm' => env('STORAGE_HASH_ALGORITHM', 'sha256'),
    ],

    'encryption' => [
        'enabled' => env('STORAGE_ENCRYPTION_ENABLED', true),
        'key' => env('STORAGE_ENCRYPTION_KEY'),
    ],
];
