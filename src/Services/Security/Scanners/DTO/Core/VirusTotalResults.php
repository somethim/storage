<?php

namespace zennit\Storage\Services\Security\Scanners\DTO\Core;

readonly class VirusTotalResults
{
    /** @var array<string, EngineResult> */
    private array $results;

    /**
     * @param array<string, EngineResult> $results
     */
    public function __construct(array $results)
    {
        $this->results = $results;
    }

    /**
     * @param array<string, array{
     *     category: string,
     *     engine_name: string,
     *     engine_update: string,
     *     engine_version: string,
     *     method: string,
     *     result: string
     * }> $results
     */
    public static function fromArray(array $results): self
    {
        $engineResults = array_map(function ($result) {
            return EngineResult::fromArray($result);
        }, $results);

        return new self($engineResults);
    }

    public function toArray(): array
    {
        return array_map(function ($result) {
            return $result->toArray();
        }, $this->results);
    }

    /**
     * @return array<string, EngineResult>
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
