<?php

namespace zennit\Storage\Services\Security;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use RuntimeException;
use Throwable;
use zennit\Storage\Models\FileScanResult;
use zennit\Storage\Notifications\FileScanComplete;
use zennit\Storage\Services\Security\Scanners\ClamAvScanner;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;
use zennit\Storage\Services\Security\Scanners\VirusTotalScanner;

/**
 * Service for scanning files for viruses before storage
 *
 * This service uses two virus scanning services to scan files before they are stored:
 * - ClamAV: A local virus scanner that uses the ClamAV engine
 * - VirusTotal: An online virus scanner that uses the VirusTotal API
 *
 * @see ClamAvScanner
 * @see VirusTotalScanner
 */
readonly class VirusScanService
{
    public function __construct(
        private ClamAvScanner $clamAvScanner,
        private VirusTotalScanner $virusTotalScanner
    ) {
    }

    /**
     * Scan file before storage
     *
     * This method scans an uploaded file for viruses using the configured scanners.
     * If any threats are found, the file is quarantined and an exception is thrown.
     *
     * @param UploadedFile $file The file to scan
     *
     * @return ScanResult The scan results
     */
    public function scanUpload(UploadedFile $file): ScanResult
    {
        // Create secure temp directory for scanning
        $tempPath = $this->createSecureTmpPath();

        try {
            // Move to secure temp location for scanning
            $tempFile = $tempPath . '/' . $file->getClientOriginalName();
            $file->move($tempPath, $file->getClientOriginalName());

            // Perform scan
            $results = $this->scan($tempFile);

            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            rmdir($tempPath);

            // If file is not clean, prevent storage
            if (!$results->is_clean) {
                throw new RuntimeException('Security threat detected in file');
            }

            return $results;

        } catch (Exception $e) {
            // Clean up on error
            if (file_exists($tempPath)) {
                $this->cleanupTempPath($tempPath);
            }
            report($e);
            throw new RuntimeException('Failed to scan file: ' . $e->getMessage());
        }
    }

    /**
     * Create a secure temporary directory for scanning
     *
     * @throws RuntimeException If the directory cannot be created
     * @return string The path to the temporary directory
     */
    private function createSecureTmpPath(): string
    {
        $tempPath = storage_path('temp/scans/' . uniqid('scan_', true));

        if (!mkdir($tempPath, 0755, true)) {
            throw new RuntimeException('Failed to create temporary scan directory');
        }

        return $tempPath;
    }

    /**
     * Scan existing file in storage
     *
     * This method scans an existing file in storage for viruses using the configured scanners.
     * If any threats are found, the file is quarantined and an exception is thrown.
     *
     * @param string $filepath The path to the file to scan
     * @param int|null $fileStorageId The ID of the FileStorage record, required if running scan on existing file
     *
     * @return ScanResult The scan results
     */
    public function scan(string $filepath, ?int $fileStorageId = null): ScanResult
    {
        try {
            $scanResults = [];
            $totalMalicious = 0;
            $combinedResults = [];

            // Run ClamAV scan
            if ($this->clamAvScanner->isAvailable()) {
                $clamResult = $this->clamAvScanner->scan($filepath);
                $scanResults['clamav'] = $clamResult;
                $totalMalicious += $clamResult->malicious;
                $combinedResults = array_merge($combinedResults, $clamResult->result ?? []);
            }

            // Run VirusTotal scan
            if ($this->virusTotalScanner->isAvailable()) {
                $vtResult = $this->virusTotalScanner->scan($filepath);
                $scanResults['virustotal'] = $vtResult;
                $totalMalicious += $vtResult->malicious;
                $combinedResults = array_merge($combinedResults, $vtResult->result ?? []);
            }

            if (empty($scanResults)) {
                throw new RuntimeException('No virus scanners available');
            }

            // Create aggregated scan result
            $aggregatedResult = new ScanResult(
                is_clean:  $totalMalicious === 0,
                scans:     implode(',', array_keys($scanResults)),
                malicious: $totalMalicious,
                result:    $combinedResults,
                scan_time: now()
            );

            // Store scan result
            $result = FileScanResult::create([
                'file_storage_id' => $fileStorageId,
                'is_clean' => $aggregatedResult->is_clean,
                'scan_result' => $scanResults,
                'scan_result_at' => $aggregatedResult->scan_time,
                'scan_details' => $combinedResults,
                'scanned_at' => $aggregatedResult->scan_time,
            ]);

            // Handle quarantine if needed
            if (!$aggregatedResult->is_clean) {
                $this->handleMaliciousFile($filepath, $result);
            }

            return $aggregatedResult;

        } catch (Exception|Throwable $e) {
            Log::error('Virus scan failed: ' . $e->getMessage(), [
                'filepath' => $filepath,
                'error' => $e->getMessage(),
            ]);
            throw new RuntimeException('Virus scan failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws Throwable
     */
    private function handleMaliciousFile(string $filepath, FileScanResult $result): void
    {
        $quarantinePath = $this->quarantineFile($filepath);
        $result->updateOrFail(['quarantine_path' => $quarantinePath]);

        $eventClass = config('scanning.scan_events.file_quarantined');
        event(new $eventClass());

        if (config('scanning.notifications.enabled')) {
            $notifiable = config('scanning.notifications.notify_user');
            Notification::send($notifiable, new FileScanComplete($result->toArray()));
        }
    }

    /**
     * Quarantine a file by moving it to a secure location
     *
     * @param string $filepath The path to the file to quarantine
     *
     * @throws RuntimeException If the file cannot be moved
     */
    private function quarantineFile(string $filepath): string
    {
        $quarantinePath = config('scanning.quarantine_path', storage_path('quarantine'));
        $quarantineFile = $quarantinePath . '/' . basename($filepath) . '_' . time();

        // Ensure quarantine directory exists
        if (!is_dir($quarantinePath)) {
            mkdir($quarantinePath, 0755, true);
        }

        // Move file to quarantine
        if (!rename($filepath, $quarantineFile)) {
            throw new RuntimeException('Failed to quarantine file');
        }

        Log::warning('File quarantined', ['original_path' => $filepath, 'quarantine_path' => $quarantineFile]);

        return $quarantineFile;
    }

    private function cleanupTempPath(string $path): void
    {
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    unlink($path . '/' . $file);
                }
            }
            rmdir($path);
        }
    }
}
