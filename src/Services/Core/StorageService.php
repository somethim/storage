<?php

namespace zennit\Storage\Services\Core;

use zennit\Storage\Contracts\StorageManager as StorageManagerContract;
use zennit\Storage\Traits\StorageHasConfigurations;

readonly class StorageService implements StorageManagerContract
{
    use StorageHasConfigurations;
}
