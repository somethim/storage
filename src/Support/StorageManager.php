<?php

namespace zennit\Storage\Support;

use zennit\Storage\Contracts\StorageManager as StorageManagerContract;
use zennit\Storage\Exceptions\StorageException;

class StorageManager implements StorageManagerContract
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function write(string $path, $contents): bool
    {
        // Implementation
    }

    public function read(string $path): mixed
    {
        // Implementation
    }

    public function delete(string $path): bool
    {
        // Implementation
    }

    public function exists(string $path): bool
    {
        // Implementation
    }

    public function move(string $from, string $to): bool
    {
        // Implementation
    }

    public function copy(string $from, string $to): bool
    {
        // Implementation
    }

    public function metadata(string $path): array
    {
        // Implementation
    }
}
