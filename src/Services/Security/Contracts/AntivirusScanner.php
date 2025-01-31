<?php

namespace zennit\Storage\Services\Security\Contracts;

use zennit\Storage\Services\Security\Scanners\DTO\ScanResult;

interface AntivirusScanner
{
    public function scan(string $filepath): ScanResult;

    public function isAvailable(): bool;
}
