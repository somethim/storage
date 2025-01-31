<?php

namespace zennit\Storage\Services\Security\Scanners\DTO\Core;

readonly class EngineResult
{
    public function __construct(
        private string $category,
        private string $engine_name,
        private string $engine_update,
        private string $engine_version,
        private string $method,
        private string $result
    ) {
    }

    public static function fromArray(array $result): self
    {
        return new self(
            category:       $result['category'],
            engine_name:    $result['engine_name'],
            engine_update:  $result['engine_update'],
            engine_version: $result['engine_version'],
            method:         $result['method'],
            result:         $result['result']
        );
    }

    public function toArray(): array
    {
        return [
            'category' => $this->category,
            'engine_name' => $this->engine_name,
            'engine_update' => $this->engine_update,
            'engine_version' => $this->engine_version,
            'method' => $this->method,
            'result' => $this->result,
        ];
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getEngineName(): string
    {
        return $this->engine_name;
    }

    public function getEngineUpdate(): string
    {
        return $this->engine_update;
    }

    public function getEngineVersion(): string
    {
        return $this->engine_version;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getResult(): string
    {
        return $this->result;
    }
}
