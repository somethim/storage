<?php

namespace zennit\Storage\Contracts;

interface StorageManager
{
    public function write(string $path, $contents): bool;
    public function read(string $path): mixed;
    public function delete(string $path): bool;
    public function exists(string $path): bool;
    public function move(string $from, string $to): bool;
    public function copy(string $from, string $to): bool;
    public function metadata(string $path): array;
}
