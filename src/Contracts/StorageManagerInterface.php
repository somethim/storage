<?php

namespace zennit\Storage\Contracts;

use Illuminate\Support\Collection;
use zennit\Storage\DTO\FileMetadata;
use zennit\Storage\DTO\StorageOperationRequest;

interface StorageManagerInterface
{
    /**
     * Store a file in the storage system
     */
    public function write(StorageOperationRequest $request): bool;

    /**
     * Get file contents
     */
    public function read(string $path): string;

    /**
     * Check if a file exists
     */
    public function exists(string $path): bool;

    /**
     * Delete a file
     */
    public function delete(string $path, bool $no_backup = false): bool;

    /**
     * Move a file
     */
    public function move(string $from, string $to): bool;

    /**
     * Copy a file
     */
    public function copy(string $from, string $to): bool;

    /**
     * Download a file from a URL and store it in the storage system
     */
    public function downloadFromUrl(string $url, string $destinationPath): bool;

    /**
     * Create a directory
     */
    public function createDirectory(string $path): bool;

    /**
     * Delete a directory
     */
    public function deleteDirectory(string $path, bool $no_backup = false): bool;

    /**
     * List contents of a directory
     *
     * @return Collection<int, FileMetadata>
     */
    public function listContents(string $directory = '', bool $recursive = false): Collection;

    /**
     * Get file metadata (size, mime type, last modified)
     */
    public function getMetadata(string $path): FileMetadata;
}
