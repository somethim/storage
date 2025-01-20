<?php

namespace zennit\Storage\Facades;

use Illuminate\Support\Facades\Facade;
use zennit\Storage\Contracts\StorageManager;

class Storage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StorageManager::class;
    }
}
