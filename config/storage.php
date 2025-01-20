<?php

return [
    'default' => env('STORAGE_DRIVER', 'local'),

    'max_file_size' => env('STORAGE_MAX_FILE_SIZE', 1024 * 1024 * 10), // 10MB

    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
    ],

    'hash_algorithm' => env('STORAGE_HASH_ALGORITHM', 'sha256'),

    'encryption' => [
        'enabled' => env('STORAGE_ENCRYPTION_ENABLED', false),
        'key' => env('STORAGE_ENCRYPTION_KEY'),
    ],
];
