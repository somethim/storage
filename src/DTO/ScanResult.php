<?php

namespace zennit\Storage\DTO;

use DateTime;

readonly class ScanResult
{
    public function __construct(
        public bool $is_clean,
        public array $scans,
        public array $threats_found,
        public DateTime $scan_time,
    ) {
    }

    public function toArray(): array
    {
        return [
            'is_clean' => $this->is_clean,
            'scans' => $this->scans,
            'threats_found' => $this->threats_found,
            'scan_time' => $this->scan_time,
        ];
    }
}
