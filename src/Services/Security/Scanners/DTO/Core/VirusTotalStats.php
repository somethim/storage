<?php

namespace zennit\Storage\Services\Security\Scanners\DTO\Core;

readonly class VirusTotalStats
{
    public function __construct(
        private int $confirmed_timeout,
        private int $failure,
        private int $harmless,
        private int $malicious,
        private int $suspicious,
        private int $timeout,
        private int $type_unsupported,
        private int $undetected,
    ) {
    }

    public static function fromArray(array $stats): self
    {
        return new self(
            confirmed_timeout: $stats['confirmed-timeout'],
            failure:           $stats['failure'],
            harmless:          $stats['harmless'],
            malicious:         $stats['malicious'],
            suspicious:        $stats['suspicious'],
            timeout:           $stats['timeout'],
            type_unsupported:  $stats['type-unsupported'],
            undetected:        $stats['undetected'],
        );
    }

    public function toArray(): array
    {
        return [
            'confirmed_timeout' => $this->confirmed_timeout,
            'failure' => $this->failure,
            'harmless' => $this->harmless,
            'malicious' => $this->malicious,
            'suspicious' => $this->suspicious,
            'timeout' => $this->timeout,
            'type_unsupported' => $this->type_unsupported,
            'undetected' => $this->undetected,
        ];
    }

    public function getMaliciousCount(): int
    {
        return $this->malicious;
    }

    public function getHarmlessCount(): int
    {
        return $this->harmless;
    }

    public function getSuspiciousCount(): int
    {
        return $this->suspicious;
    }

    public function getFailureCount(): int
    {
        return $this->failure;
    }
}
