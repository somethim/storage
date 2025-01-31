<?php

namespace zennit\Storage\Services\Security\Scanners;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Socket\Raw\Factory;
use Xenolope\Quahog\Client;
use zennit\Storage\Services\Security\Contracts\AntivirusScanner;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;

class ClamAvScanner implements AntivirusScanner
{
    private Client $scanner;

    public function __construct()
    {
        $this->initScanner();
    }

    private function initScanner(): void
    {
        try {
            $factory = new Factory();
            $socket = $factory->createClient(config('scanning.clamav'));
            $this->scanner = new Client($socket);
            $this->scanner->ping();
        } catch (Exception $e) {
            Log::error('ClamAV connection failed: ' . $e->getMessage());
            throw new RuntimeException('Failed to connect to ClamAV: ' . $e->getMessage());
        }
    }

    public function scan(string $filepath): ScanResult
    {
        $cacheKey = config('scanning.cache.prefix') . md5($filepath);
        if ($cachedResult = Cache::get($cacheKey)) {
            return $cachedResult;
        }

        $result = $this->scanner->scanFile($filepath);

        $status = $result['status'] ?? 'FOUND';
        $scanResult = new ScanResult(
            is_clean:  $status === 'OK',
            scans:     'clamav',
            malicious: $result['reason'] ? 1 : 0,
            result:    $result['reason'] ? [$result['reason']] : null,
            scan_time: now(),
        );

        Cache::put($cacheKey, $scanResult, now()->addHours(config('scanning.cache.ttl')));

        return $scanResult;
    }

    public function isAvailable(): bool
    {
        try {
            return $this->scanner->ping();
        } catch (Exception $e) {
            report($e);

            return false;
        }
    }
}
