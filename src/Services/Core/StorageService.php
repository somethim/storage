<?php

namespace zennit\Storage\Services\Core;

use Exception;
use Http;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Collection;
use zennit\Storage\Contracts\StorageManagerInterface;
use zennit\Storage\Services\Core\DTO\FileMetadata;
use zennit\Storage\Services\Core\DTO\StorageOperationRequest;

readonly class StorageService implements StorageManagerInterface
{
    public function __construct(private FilesystemManager $storage)
    {
    }

    public function write(StorageOperationRequest $request): bool
    {
        return $this->storage->disk(config('storage.disk'))->put($request->path, $request->contents);
    }

    public function read(string $path): string
    {
        return $this->storage->disk(config('storage.disk'))->get($path);
    }

    public function move(string $from, string $to): bool
    {
        return $this->storage->disk(config('storage.disk'))->move($from, $to);
    }

    public function copy(string $from, string $to): bool
    {
        return $this->storage->disk(config('storage.disk'))->copy($from, $to);
    }

    public function downloadFromUrl(string $url, string $destinationPath): bool
    {
        try {
            $response = Http::get($url);

            if (!$response->successful()) {
                return false;
            }

            return $this->storage->disk(config('storage.disk'))->put(
                $destinationPath,
                $response->body()
            );
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }

    public function exists(string $path): bool
    {
        return $this->storage->disk(config('storage.disk'))->exists($path);
    }

    public function delete(string $path, bool $no_backup = false): bool
    {
        if (!$no_backup) {
            return $this->storage->disk(config('storage.disk'))->delete($path);
        }

        // todo: implement backup
        return false;
    }

    public function createDirectory(string $path): bool
    {
        return $this->storage->disk(config('storage.disk'))->makeDirectory($path);
    }

    public function deleteDirectory(string $path, bool $no_backup = false): bool
    {
        if (!$no_backup) {
            return $this->storage->disk(config('storage.disk'))->deleteDirectory($path);
        }

        // todo: implement backup
        return false;
    }

    public function listContents(string $directory = '', bool $recursive = false): Collection
    {
        $contents = $this->storage->disk(config('storage.disk'))->allFiles($directory);

        return collect($contents);
    }

    public function getMetadata(string $path): FileMetadata
    {
        $size = $this->storage->disk(config('storage.disk'))->size($path);
        $mimeType = $this->storage->disk(config('storage.disk'))->mimeType($path);
        $lastModified = $this->storage->disk(config('storage.disk'))->lastModified($path);
        $timestamp = $this->storage->disk(config('storage.disk'))->lastModified($path);

        return new FileMetadata($size, $mimeType, $lastModified, $path, $timestamp);
    }
}
