<?php

namespace zennit\Storage\Facades;

use Illuminate\Support\Facades\Facade;
use zennit\Storage\Contracts\StorageManagerInterface;

class Storage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StorageManagerInterface::class;
    }
}
