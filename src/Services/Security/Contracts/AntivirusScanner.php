<?php

namespace zennit\Storage\Services\Security\Contracts;

interface AntivirusScanner
{
    public function scan(string $filepath): array;
    public function isAvailable(): bool;
} 