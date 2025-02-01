<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileTag extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
    ];
}
