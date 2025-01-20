<?php

namespace zennit\Storage\Services\Security;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;

readonly class ValidationService
{
    public function __construct(
        private HashingService $hashingService,
        private VirusScanService $virusScanner
    ) {
    }

    public function validateFile(UploadedFile $file): array
    {
        $results = [
            'passed' => true,
            'errors' => [],
        ];

        // Size validation
        if (!$this->validateFileSize($file)) {
            $results['errors'][] = 'File exceeds maximum size limit';
        }

        // Extension validation
        if (!$this->validateExtension($file)) {
            $results['errors'][] = 'File type not allowed';
        }

        // MIME type validation
        if (!$this->validateMimeType($file)) {
            $results['errors'][] = 'Invalid file type';
        }

        // Malware scan
        $scanResult = $this->virusScanner->scan($file->path());
        if (!$scanResult['is_clean']) {
            $results['errors'][] = 'File failed security scan';
        }

        // File integrity check
        if (!$this->validateFileIntegrity($file)) {
            $results['errors'][] = 'File integrity check failed';
        }

        $results['passed'] = empty($results['errors']);

        return $results;
    }

    private function validateFileSize(UploadedFile $file): bool
    {
        $maxSize = Config::get('storage.max_file_size', 100 * 1024 * 1024); // 100MB default

        return $file->getSize() <= $maxSize;
    }

    private function validateExtension(UploadedFile $file): bool
    {
        $allowedExtensions = Config::get('storage.allowed_extensions', []);

        return in_array($file->getClientOriginalExtension(), $allowedExtensions);
    }

    private function validateMimeType(UploadedFile $file): bool
    {
        $allowedMimes = Config::get('storage.allowed_mimes', []);

        return in_array($file->getMimeType(), $allowedMimes);
    }

    private function validateFileIntegrity(UploadedFile $file): bool
    {
        // Generate hash and compare with any provided hash
        $calculatedHash = $this->hashingService->hashFile($file->path());
        $providedHash = request()->input('file_hash');

        return !$providedHash || $calculatedHash === $providedHash;
    }
}
