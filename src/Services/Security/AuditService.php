<?php

namespace zennit\Storage\Services\Security;

use Illuminate\Support\Facades\Log;

readonly class AuditService
{
    public function __construct(
        private HashingService $hashingService
    ) {
    }

    public function logFileOperation(
        string $operation,
        string $filepath,
        ?string $userId = null,
        array $metadata = []
    ): void {
        $entry = [
            'timestamp' => now(),
            'operation' => $operation,
            'filepath' => $filepath,
            'user_id' => $userId ?? 'system',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'file_fingerprint' => $this->hashingService->generateFileFingerprint($filepath),
            'metadata' => $metadata,
        ];

        Log::channel('file_operations')->info('File Operation', $entry);
    }
}
