<?php

namespace zennit\Storage\Console\Commands;

use Illuminate\Console\Command;
use zennit\Storage\Jobs\ScanFile;
use zennit\Storage\Models\FileStorage;

class RescanStoredFiles extends Command
{
    protected $signature = 'storage:rescan-files {--older-than=30d : Only rescan files older than this}';

    protected $description = 'Rescan stored files for viruses';

    public function handle(): void
    {
        $olderThan = $this->option('older-than');
        $date = now()->sub($olderThan);

        FileStorage::query()
            ->where('last_scanned_at', '<', $date)
            ->orWhereNull('last_scanned_at')
            ->chunk(100, function ($files) {
                foreach ($files as $file) {
                    ScanFile::dispatch($file->path, $file->id)
                        ->onQueue('virus-scans');
                }
            });

        $this->info('Rescan jobs dispatched successfully.');
    }
}
