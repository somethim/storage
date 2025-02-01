<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileStorage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'path',
        'disk',
        'mime_type',
        'size',
        'hash',
        'metadata',
        'encryption_key',
        'is_compressed',
        'storable_id',
        'storable_type',
        'last_scanned_at',
        'original_file_id',
        'reference_count',
        'last_backup_at',
        'backup_status',
        'status',
    ];

    protected $casts = [
        'path' => 'string',
        'disk' => 'string',
        'mime_type' => 'string',
        'size' => 'integer',
        'hash' => 'string',
        'metadata' => 'array',
        'encryption_key' => 'string',
        'is_compressed' => 'boolean',
        'storable_id' => 'integer',
        'storable_type' => 'string',
        'last_scanned_at' => 'datetime',
        'original_file_id' => 'integer',
        'reference_count' => 'integer',
        'last_backup_at' => 'datetime',
        'backup_status' => 'array',
        'status' => 'string',
    ];
}
