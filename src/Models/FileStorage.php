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
    ];
}
