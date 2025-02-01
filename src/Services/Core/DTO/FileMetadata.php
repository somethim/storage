<?php

namespace zennit\Storage\Services\Core\DTO;

readonly class FileMetadata
{
    public function __construct(
        public int $size,
        public string $mimeType,
        public int $lastModified,
        public string $path,
        public string $timestamp,
    ) {
    }
}
