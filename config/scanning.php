<?php

use zennit\Storage\Events\FileQuarantinedEvent;

return [
    'clamav' => env('CLAMAV_HOST', 'localhost') . ':' . env('CLAMAV_PORT', 3310),
    'virustotal' => [
        'api_key' => env('VIRUSTOTAL_API_KEY'),
        'scan_sm' => [
            'size' => 33554432, // 32MB
            'url' => 'virustotal.com/api/v3/files',
            'method' => 'POST',
        ],
        'scan_lg' => [
            'size' => 681574400, // 650MB
            'url' => 'virustotal.com/api/v3/urls',
            'method' => 'GET',
        ],
        'scan_report' => 'https://www.virustotal.com/api/v3/files/%s',
    ],

    'quarantine_path' => env('QUARANTINE_PATH', storage_path('quarantine')),

    'scan_cache_ttl' => env('SCAN_CACHE_TTL', 24 * 60 * 60), // 24 hours

    'scan_events' => [
        'file_quarantined' => FileQuarantinedEvent::class,
    ],

    'scan_log_channel' => env('SCAN_LOG_CHANNEL', 'file_operations'),

    'max_file_size' => env('MAX_FILE_SIZE', 1024 * 1024 * 1024), // 1GB

    'allowed_extensions' => ['txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
    'allowed_mimes' => [
        'text/plain',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ],

    'scan_schedule' => [
        'interval' => 'daily',
        'time' => '00:00',
    ],

    'cache' => [
        'ttl' => 24, // Cache TTL in hours
        'prefix' => 'virus_scan_',
    ],

    'hash_algorithms' => ['sha256', 'md5'],
];
