<?php

namespace zennit\Storage\Services\Security\Scanners;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use zennit\Storage\Services\Security\Contracts\AntivirusScanner;
use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;
use zennit\Storage\Services\Security\Scanners\DTO\VirusTotalResponse;

class VirusTotalScanner implements AntivirusScanner
{
    public function __construct(private string $apiKey, private Client $client)
    {
        $this->client = new Client();
        $this->apiKey = config('scanning.services.virustotal.api_key');

        if (empty($this->apiKey)) {
            throw new RuntimeException('VirusTotal API key not configured');
        }
    }

    public function isAvailable(): bool
    {
        try {
            $response = $this->client->request('GET', config('scanning.services.virustotal.url.base'), [
                'headers' => $this->getHeaders(),
            ]);

            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            report($e);

            return false;
        }
    }

    public function scan(string $filepath): ScanResult
    {
        $cacheKey = 'virustotal_' . md5($filepath);
        if ($cachedResult = Cache::get($cacheKey)) {
            return $cachedResult;
        }

        $uploadUrl = $this->getUploadUrl();
        $analysisId = $this->uploadFile($uploadUrl, $filepath);
        $result = $this->getAnalysisResults($analysisId);

        $scanResult = new ScanResult(
            is_clean:  $result->last_analysis_stats->getMaliciousCount() === 0,
            scans:     'virustotal',
            malicious: $result->last_analysis_stats->getMaliciousCount(),
            result:    $result->last_analysis_results->toArray(),
            scan_time: now(),
        );

        Cache::put($cacheKey, $scanResult, now()->addHours(config('scanning.cache.ttl')));

        return $scanResult;
    }

    private function getUploadUrl(): string
    {
        return $this->makeRequest(config('scanning.services.virustotal.url.upload'), 'GET')['data'];
    }

    private function makeRequest(string $url, string $method, array $data = []): array
    {
        try {
            $response = $this->client->request($method, $url, ['headers' => $this->getHeaders(), 'json' => $data]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('VirusTotal scan failed: ' . $e->getMessage());
            throw new RuntimeException('VirusTotal scan failed: ' . $e->getMessage());
        }
    }

    private function getHeaders(): array
    {
        return [
            'accept' => 'application/json',
            'x-apikey' => $this->apiKey,
        ];
    }

    private function uploadFile(string $uploadUrl, string $filepath): string
    {
        return $this->makeRequest($uploadUrl, 'POST', [
            'multipart' => [[
                'name' => 'file',
                'contents' => fopen($filepath, 'r'),
                'filename' => basename($filepath),
            ]],
        ])['data']['id'];
    }

    private function getAnalysisResults(string $analysisId): VirusTotalResponse
    {
        $maxRetries = config('scanning.services.virustotal.retry');
        $retryDelay = config('scanning.services.virustotal.delay');

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            sleep($retryDelay);

            $url = config('scanning.services.virustotal.url.report') . '/' . $analysisId;
            $response = $this->makeRequest($url, 'GET');
            $attributes = $response['data']['attributes'];

            if ($attributes['status'] !== 'queued') {
                return VirusTotalResponse::fromArray(
                    $attributes['last_analysis_results'],
                    $attributes['last_analysis_stats'],
                );
            }
        }

        throw new RuntimeException('Analysis timed out');
    }
}
