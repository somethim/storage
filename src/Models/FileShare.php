<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileShare extends Model
{
    protected $fillable = [
        'file_storage_id',
        'token',
        'expires_at',
        'download_limit',
        'download_count',
        'access_restrictions',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'token' => 'string',
        'expires_at' => 'datetime',
        'download_limit' => 'integer',
        'download_count' => 'integer',
        'access_restrictions' => 'array',
    ];
}
