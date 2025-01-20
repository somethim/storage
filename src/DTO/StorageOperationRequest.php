<?php

namespace zennit\Storage\DTO;

use Illuminate\Http\UploadedFile;

readonly class StorageOperationRequest
{
    public function __construct(
        public string $path,
        public UploadedFile $contents,
        public string $disk,
    ) {
    }
}
