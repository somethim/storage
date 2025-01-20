<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    protected $fillable = [
        'file_storage_id',
        'version_path',
        'size',
        'hash',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'version_path' => 'string',
        'size' => 'integer',
        'hash' => 'string',
        'metadata' => 'array',
        'created_by' => 'integer',
    ];
}
