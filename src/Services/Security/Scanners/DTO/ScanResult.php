<?php

namespace zennit\Storage\Services\Security\Scanners\DTO;

use Illuminate\Support\Carbon;

readonly class ScanResult
{
    public function __construct(
        public bool $is_clean,
        public string $scans,
        public int $malicious,
        public ?array $result,
        public ?string $quarantine_path = null,
        public Carbon $scan_time,
    ) {
    }

    public function fromArray(array $scanResult): self
    {
        return new self(
            is_clean:        $scanResult['is_clean'],
            scans:           $scanResult['scans'],
            malicious:       $scanResult['malicious'],
            result:          $scanResult['result'],
            quarantine_path: $scanResult['quarantine_path'] ?? null,
            scan_time:       new Carbon($scanResult['scan_time']),
        );
    }

    public function toArray(): array
    {
        return [
            'is_clean' => $this->is_clean,
            'scans' => $this->scans,
            'malicious' => $this->malicious,
            'result' => $this->result,
            'scan_time' => $this->scan_time,
            'quarantine_path' => $this->quarantine_path,
        ];
    }
}
