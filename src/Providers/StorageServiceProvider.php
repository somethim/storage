<?php

namespace zennit\Storage\Providers;

use Illuminate\Support\ServiceProvider;
use zennit\Storage\Contracts\StorageManager;
use zennit\Storage\Services\Core\StorageService;

class StorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/storage.php', 'storage');

        $this->app->singleton(StorageManager::class, function ($app) {
            return $app->make(StorageService::class);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/storage.php' => config_path('storage.php'),
        ], 'storage-config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
