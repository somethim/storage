<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileScanResult extends Model
{
    protected $fillable = [
        'file_storage_id',
        'is_clean',
        'scan_details',
        'scanned_at',
        'scan_result',
        'scan_result_at',
        'quarantine_path',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'is_clean' => 'boolean',
        'scan_result' => 'array',
        'scanned_at' => 'datetime',
    ];
}
