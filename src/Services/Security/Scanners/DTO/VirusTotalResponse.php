<?php

namespace zennit\Storage\Services\Security\Scanners\DTO;

use zennit\Storage\Services\Security\Scanners\DTO\Core\VirusTotalResults;
use zennit\Storage\Services\Security\Scanners\DTO\Core\VirusTotalStats;

readonly class VirusTotalResponse
{
    public function __construct(
        public VirusTotalResults $last_analysis_results,
        public VirusTotalStats $last_analysis_stats,
    ) {
    }

    public function toArray(): array
    {
        return [
            'last_analysis_results' => $this->last_analysis_results,
            'last_analysis_stats' => $this->last_analysis_stats,
        ];
    }

    public function fromArray(array $last_analysis_results, $last_analysis_stats): self
    {
        return new self(
            last_analysis_results: VirusTotalResults::fromArray($last_analysis_results),
            last_analysis_stats:   VirusTotalStats::fromArray($last_analysis_stats),
        );
    }
}
