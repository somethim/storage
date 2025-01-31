<?php

namespace zennit\Storage\Services\Security\Scanners\DTO;

use DateTime;

readonly class ScanResult
{
    public function __construct(
        public bool $is_clean,
        public string $scans,
        public int $malicious,
        public ?array $result,
        public DateTime $scan_time,
    ) {
    }

    public function toArray(): array
    {
        return [
            'is_clean' => $this->is_clean,
            'scans' => $this->scans,
            'malicious' => $this->malicious,
            'result' => $this->result,
            'scan_time' => $this->scan_time,
        ];
    }
}
