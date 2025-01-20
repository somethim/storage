<?php

namespace zennit\Storage\Traits;

trait StorageHasConfigurations
{
    // Storage Configuration
    public function getDisk(): string
    {
        return config('storage.disk', 'local');
    }

    public function getMaxFileSize(): int
    {
        return config('storage.max_file_size', 1024 * 1024 * 10);
    }

    /** @return array<string> */
    public function getAllowedMimeTypes(): array
    {
        return config('storage.allowed_mime_types', []);
    }

    // Hash Configuration
    public function getHashEnabled(): bool
    {
        return config('storage.hash_algorithm.enabled', true);
    }

    public function getHashAlgorithm(): string
    {
        return config('storage.hash_algorithm.algorithm', 'sha256');
    }

    // Encryption Configuration
    public function getEncryptionEnabled(): bool
    {
        return config('storage.encryption.enabled', true);
    }

    public function getEncryptionKey(): string
    {
        return config('storage.encryption.key');
    }
}
