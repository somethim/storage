<?php

namespace zennit\Storage\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use zennit\Storage\Services\Security\VirusScanService;

class ScanFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly string $filepath,
        private readonly ?int $fileId = null
    ) {
    }

    public function handle(VirusScanService $scanner): void
    {
        $scanner->scan($this->filepath, $this->fileId);
    }

    public function tags(): array
    {
        return ['virus-scan', "file:{$this->fileId}"];
    }

    public function retryUntil(): \DateTime
    {
        return now()->addHours(24);
    }
}
