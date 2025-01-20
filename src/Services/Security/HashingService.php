<?php

namespace zennit\Storage\Services\Security;

readonly class HashingService
{
    public function verifyHash(string $filepath, string $expectedHash): bool
    {
        $actualHash = $this->hashFile($filepath);

        return hash_equals($expectedHash, $actualHash);
    }

    public function hashFile(string $filepath): string
    {
        return hash_file('sha256', $filepath);
    }

    public function generateFileFingerprint(string $filepath): array
    {
        return [
            'sha256' => hash_file('sha256', $filepath),
            'md5' => hash_file('md5', $filepath),
            'timestamp' => filemtime($filepath),
            'size' => filesize($filepath),
        ];
    }
}
