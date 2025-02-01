<?php

namespace zennit\Storage\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use zennit\Storage\Console\Commands\RescanStoredFiles;
use zennit\Storage\Contracts\StorageManagerInterface;
use zennit\Storage\Events\FileUploaded;
use zennit\Storage\Events\Security\FileQuarantinedEvent;
use zennit\Storage\Listeners\ScanForVirus;
use zennit\Storage\Listeners\Security\NotifyFileQuarantined;
use zennit\Storage\Services\Core\StorageService;
use zennit\Storage\Services\Security\Scanners\ClamAvScanner;
use zennit\Storage\Services\Security\Scanners\VirusTotalScanner;
use zennit\Storage\Services\Security\VirusScanService;

class StorageServiceProvider extends ServiceProvider
{
    protected $listen = [
        FileUploaded::class => [
            ScanForVirus::class,
        ],
        FileQuarantinedEvent::class => [
            NotifyFileQuarantined::class,
        ],
    ];

    public function register(): void
    {
        // Register Storage Service
        $this->app->singleton(StorageService::class, function ($app) {
            return new StorageService(
                $app->make(FilesystemManager::class)
            );
        });

        // Bind interface to implementation
        $this->app->bind(StorageManagerInterface::class, StorageService::class);

        // Register Virus Scanners
        $this->app->singleton(ClamAvScanner::class);
        $this->app->singleton(VirusTotalScanner::class);

        // Register Virus Scan Service
        $this->app->singleton(VirusScanService::class, function ($app) {
            return new VirusScanService(
                $app->make(ClamAvScanner::class),
                $app->make(VirusTotalScanner::class)
            );
        });
    }

    public function boot(): void
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/storage.php',
            'storage'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/scanning.php',
            'scanning'
        );

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                RescanStoredFiles::class,
            ]);
        }

        // Register scheduled tasks
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Run rescan every week
            $schedule->command('storage:rescan-files')
                ->weekly()
                ->onQueue('virus-scans');
        });

        // Register event listeners
        $this->app['events']->listen(
            FileUploaded::class,
            ScanForVirus::class
        );
    }

    public function provides(): array
    {
        return [
            StorageService::class,
            StorageManagerInterface::class,
            VirusScanService::class,
            ClamAvScanner::class,
            VirusTotalScanner::class,
        ];
    }
}
