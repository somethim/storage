<?php

namespace zennit\Storage\DTO;

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
