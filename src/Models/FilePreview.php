<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FilePreview extends Model
{
    protected $fillable = [
        'file_storage_id',
        'type',
        'path',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'type' => 'string',
        'path' => 'string',
        'mime_type' => 'string',
        'size' => 'integer',
    ];
}
