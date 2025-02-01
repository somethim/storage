<?php

namespace zennit\Storage\Models;

use Illuminate\Database\Eloquent\Model;

class FileTagAssociation extends Model
{
    protected $fillable = [
        'file_storage_id',
        'file_tag_id',
    ];

    protected $casts = [
        'file_storage_id' => 'integer',
        'tag_id' => 'integer',
    ];
}
