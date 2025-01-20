<?php

namespace zennit\Storage\DTO;

use File;
use zennit\Storage\Traits\StorageHasConfigurations;

readonly class StorageOperationRequest
{
    use StorageHasConfigurations;

    public function __construct(
        public string $path,
        public File $file,
        public string $disk = 'local',
    ) {
    }
}
