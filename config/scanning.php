<?php

return [
    /*
    |--------------------------------------------------------------------------
    | File Handling Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for file handling, including the maximum
    | file size, hash algorithms, and allowed file types.
    |
    */
    'file' => [
        'max_size' => env('MAX_FILE_SIZE', 1024 * 1024 * 1024), // 1GB
        'hash_algorithms' => ['sha256', 'md5'],
        'allowed' => [
            'extensions' => ['txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'mimes' => [
                'text/plain',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scanning Services Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for the virus scanning services, including
    | the ClamAV service and the VirusTotal service.
    |
    */
    'services' => [
        'clamav' => [
            'host' => env('CLAMAV_HOST', 'localhost'),
            'port' => env('CLAMAV_PORT', 3310),
            'timeout' => env('CLAMAV_TIMEOUT', 30), // seconds
        ],

        'virustotal' => [
            'api_key' => env('VIRUSTOTAL_API_KEY'),
            'url' => [
                'test' => 'https://www.virustotal.com/api/v3/references', // todo: find endpoint that I can use to test if service is available
                'upload' => 'https://www.virustotal.com/api/v3/files/upload_url',
                'report' => 'https://www.virustotal.com/api/v3/files',
            ],
            'retry' => 3,
            'delay' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for the cache, including the cache
    | enabled flag, the cache time-to-live (TTL), and the cache prefix.
    |
    */
    'cache' => [
        'enabled' => env('SCAN_CACHE_ENABLED', true),
        'ttl' => env('SCAN_CACHE_TTL', 24 * 60 * 60), // 24 hours
        'prefix' => 'virus_scan_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security and Storage
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for security and storage, including the
    | quarantine path for infected files.
    |
    */
    'security' => [
        'tmp_path' => env('TMP_PATH', storage_path('tmp')),
        'quarantine_path' => env('QUARANTINE_PATH', storage_path('quarantine')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging and Notifications
    |--------------------------------------------------------------------------
    |
    | This section defines the settings for logging and notifications, including
    | the log channel and notification settings.
    |
    */
    'notifications' => [
        'enabled' => env('SCAN_NOTIFICATIONS_ENABLED', true),
        'notify_user' => env('SCAN_NOTIFY_USER'),
        'quarantine_notify_emails' => array_filter(
            explode(',', env('SCAN_QUARANTINE_NOTIFY_EMAILS', ''))
        ),
        'log_channel' => env('SCAN_LOG_CHANNEL', 'file_operations'),
    ],
];
