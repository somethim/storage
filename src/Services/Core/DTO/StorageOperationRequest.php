<?php

namespace zennit\Storage\Services\Core\DTO;

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
