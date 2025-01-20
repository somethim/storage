<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileAudit extends Model
{
    protected $fillable = [
        'file_storage_id',
        'action',
        'actor',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'action' => 'string',
        'actor' => 'integer',
        'details' => 'array',
        'ip_address' => 'string',
        'user_agent' => 'string',
    ];
}
