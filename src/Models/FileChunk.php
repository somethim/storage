<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileChunk extends Model
{
    protected $fillable = [
        'upload_id',
        'file_storage_id',
        'chunk_number',
        'total_chunks',
        'chunk_path',
        'chunk_size',
        'expires_at',
        'chunk_hash',
        'is_validated',
        'validation_errors',
    ];

    protected $casts = [
        'upload_id' => 'string',
        'file_storage_id' => 'integer',
        'chunk_number' => 'integer',
        'total_chunks' => 'integer',
        'chunk_path' => 'string',
        'chunk_size' => 'integer',
        'expires_at' => 'datetime',
        'chunk_hash' => 'string',
        'is_validated' => 'boolean',
        'validation_errors' => 'array',
    ];
}
