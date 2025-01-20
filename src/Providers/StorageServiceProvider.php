<?php

namespace zennit\Storage\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\FilesystemManager;
use zennit\Storage\Services\Core\StorageService;
use zennit\Storage\Services\Security\VirusScanService;
use zennit\Storage\Services\Security\Scanners\ClamAvScanner;
use zennit\Storage\Services\Security\Scanners\VirusTotalScanner;
use zennit\Storage\Console\Commands\RescanStoredFiles;
use zennit\Storage\Contracts\StorageManagerInterface;

class StorageServiceProvider extends ServiceProvider
{
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
            __DIR__.'/../../config/storage.php', 'storage'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../../config/scanning.php', 'scanning'
        );

        // Register migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

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
            \zennit\Storage\Events\FileUploaded::class,
            \zennit\Storage\Listeners\ScanForVirus::class
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
