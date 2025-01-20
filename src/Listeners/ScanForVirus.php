<?php

namespace zennit\Storage\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use zennit\Storage\Events\FileUploaded;
use zennit\Storage\Jobs\ScanFile;

class ScanForVirus implements ShouldQueue
{
    public function handle(FileUploaded $event): void
    {
        ScanFile::dispatch($event->filepath, $event->fileId)
            ->onQueue('virus-scans');
    }
}
