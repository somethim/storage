<?php

namespace zennit\Storage\Services\Security\Scanners;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use zennit\Storage\Services\Security\Contracts\AntivirusScanner;

class VirusTotalScanner implements AntivirusScanner
{
    private Client $client;

    private string $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('scanning.virustotal.api_key');

        if (empty($this->apiKey)) {
            throw new RuntimeException('VirusTotal API key not configured');
        }
    }

    public function scan(string $filepath): array
    {
        $cacheKey = 'virustotal_' . md5($filepath);
        if ($cachedResult = Cache::get($cacheKey)) {
            return $cachedResult;
        }

        try {
            $uploadUrl = $this->getUploadUrl();
            $analysisId = $this->uploadFile($uploadUrl, $filepath);
            $result = $this->getAnalysisResults($analysisId);

            $scanResult = [
                'scanner' => 'virustotal',
                'is_clean' => $result['status'] === 'completed' && $result['stats']['malicious'] === 0,
                'threat_details' => $result['stats'],
                'scan_time' => now(),
                'scan_id' => $analysisId,
            ];

            Cache::put($cacheKey, $scanResult, now()->addHours(config('scanning.cache.ttl')));

            return $scanResult;

        } catch (GuzzleException $e) {
            Log::error('VirusTotal scan failed: ' . $e->getMessage());
            throw new RuntimeException('VirusTotal scan failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws GuzzleException
     */
    private function getUploadUrl(): string
    {
        $response = $this->client->request('GET', 'https://www.virustotal.com/api/v3/files/upload_url', [
            'headers' => $this->getHeaders(),
        ]);

        return json_decode($response->getBody()->getContents(), true)['data'];
    }

    private function getHeaders(): array
    {
        return [
            'accept' => 'application/json',
            'x-apikey' => $this->apiKey,
        ];
    }

    /**
     * @throws GuzzleException
     */
    private function uploadFile(string $uploadUrl, string $filepath): string
    {
        $response = $this->client->request('POST', $uploadUrl, [
            'headers' => $this->getHeaders(),
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filepath, 'r'),
                    'filename' => basename($filepath),
                ],
            ],
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return $data['data']['id'];
    }

    /**
     * @throws GuzzleException
     */
    private function getAnalysisResults(string $analysisId): array
    {
        $maxRetries = config('scanning.virustotal.max_retries', 5);
        $retryDelay = config('scanning.virustotal.retry_delay', 15);

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            sleep($retryDelay);

            $response = $this->client->request('GET', "https://www.virustotal.com/api/v3/analyses/$analysisId", [
                'headers' => $this->getHeaders(),
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $status = $result['data']['attributes']['status'];

            if ($status !== 'queued') {
                return $result['data']['attributes'];
            }
        }

        throw new RuntimeException('Analysis timed out');
    }

    public function isAvailable(): bool
    {
        try {
            $response = $this->client->request('GET', 'https://www.virustotal.com/api/v3/users/current', [
                'headers' => $this->getHeaders(),
            ]);

            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            report($e);

            return false;
        }
    }
}
