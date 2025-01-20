#!/bin/bash

# Create main directory structure
mkdir -p src/{Adapters,Contracts,Exceptions,Support,Providers,Facades}
mkdir -p tests/{Unit,Feature,Benchmarks}
mkdir -p config
mkdir -p database/migrations
mkdir -p scripts

# Create composer.json
cat > composer.json << EOL
{
    "name": "zennit/storage",
    "description": "Storage management for Laravel",
    "version": "5.0.3",
    "type": "library",
    "license": "MIT",
    "keywords": [
        "laravel",
        "storage",
        "filesystem",
        "backup",
        "encryption",
        "compression",
        "virusscan"
    ],
    "homepage": "https://github.com/somethim/storage",
    "support": {
        "issues": "https://github.com/somethim/storage/issues",
        "source": "https://github.com/somethim/storage"
    },
    "readme": "README.md",
    "authors": [
        {
            "name": "zennit",
            "email": "contact@zennit.dev",
            "homepage": "https://zennit.dev"
        }
    ],
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.2"
    },
    "require-dev": {
        "laravel/pint": "^1.18",
        "phpunit/phpunit": "^11.5",
        "orchestra/testbench": "^9.8",
        "mockery/mockery": "^1.6",
        "phpbench/phpbench": "^1.3"
    },
    "autoload": {
        "psr-4": {
            "zennit\\Storage\\": "src/"
        },
        "files": [
            "src/helpers.php"
        ]
    },
    "extra": {
        "laravel": {
            "providers": [
                "zennit\\Storage\\Providers\\StorageServiceProvider"
            ],
            "config": {
                "storage": "config/storage.php"
            },
            "aliases": {
                "Storage": "zennit\\Storage\\Facades\\Storage"
            }
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true,
    "scripts": {
        "version-patch": [
            "php -f scripts/version.php patch"
        ],
        "version-minor": [
            "php -f scripts/version.php minor"
        ],
        "version-major": [
            "php -f scripts/version.php major"
        ]
    },
    "config": {
        "sort-packages": true
    }
}
EOL

# Create Service Provider
cat > src/Providers/StorageServiceProvider.php << EOL
<?php

namespace zennit\Storage\Providers;

use Illuminate\Support\ServiceProvider;
use zennit\Storage\Contracts\StorageManager;

class StorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        \$this->mergeConfigFrom(__DIR__.'/../../config/storage.php', 'storage');

        \$this->app->singleton(StorageManager::class, function (\$app) {
            return new StorageManager(\$app['config']['storage']);
        });
    }

    public function boot(): void
    {
        \$this->publishes([
            __DIR__.'/../../config/storage.php' => config_path('storage.php'),
        ], 'storage-config');

        \$this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
EOL

# Create Storage Facade
cat > src/Facades/Storage.php << EOL
<?php

namespace zennit\Storage\Facades;

use Illuminate\Support\Facades\Facade;
use zennit\Storage\Contracts\StorageManager;

class Storage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return StorageManager::class;
    }
}
EOL

# Create helpers file
cat > src/helpers.php << EOL
<?php

if (! function_exists('storage_path')) {
    function storage_path(string \$path = ''): string
    {
        return app('path.storage').(\$path ? DIRECTORY_SEPARATOR.\$path : \$path);
    }
}
EOL

# Create version script
cat > scripts/version.php << EOL
<?php

if (\$argc !== 2) {
    die("Usage: php version.php [major|minor|patch]\n");
}

\$composerFile = __DIR__ . '/../composer.json';
\$composer = json_decode(file_get_contents(\$composerFile), true);
\$version = explode('.', \$composer['version']);

switch (\$argv[1]) {
    case 'major':
        \$version[0]++;
        \$version[1] = 0;
        \$version[2] = 0;
        break;
    case 'minor':
        \$version[1]++;
        \$version[2] = 0;
        break;
    case 'patch':
        \$version[2]++;
        break;
    default:
        die("Invalid version type. Use major, minor, or patch.\n");
}

\$composer['version'] = implode('.', \$version);
file_put_contents(\$composerFile, json_encode(\$composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Version updated to " . \$composer['version'] . "\n";
EOL

# Create Storage Manager Contract
cat > src/Contracts/StorageManager.php << EOL
<?php

namespace zennit\Storage\Contracts;

interface StorageManager
{
    public function write(string \$path, \$contents): bool;
    public function read(string \$path): mixed;
    public function delete(string \$path): bool;
    public function exists(string \$path): bool;
    public function move(string \$from, string \$to): bool;
    public function copy(string \$from, string \$to): bool;
    public function metadata(string \$path): array;
}
EOL

# Create Storage Manager Implementation
cat > src/Support/StorageManager.php << EOL
<?php

namespace zennit\Storage\Support;

use zennit\Storage\Contracts\StorageManager as StorageManagerContract;
use zennit\Storage\Exceptions\StorageException;

class StorageManager implements StorageManagerContract
{
    protected array \$config;

    public function __construct(array \$config)
    {
        \$this->config = \$config;
    }

    public function write(string \$path, \$contents): bool
    {
        // Implementation
    }

    public function read(string \$path): mixed
    {
        // Implementation
    }

    public function delete(string \$path): bool
    {
        // Implementation
    }

    public function exists(string \$path): bool
    {
        // Implementation
    }

    public function move(string \$from, string \$to): bool
    {
        // Implementation
    }

    public function copy(string \$from, string \$to): bool
    {
        // Implementation
    }

    public function metadata(string \$path): array
    {
        // Implementation
    }
}
EOL

# Create Storage Exception
cat > src/Exceptions/StorageException.php << EOL
<?php

namespace zennit\Storage\Exceptions;

class StorageException extends \Exception
{
    //
}
EOL

# Create config file
cat > config/storage.php << EOL
<?php

return [
    'default' => env('STORAGE_DRIVER', 'local'),

    'max_file_size' => env('STORAGE_MAX_FILE_SIZE', 1024 * 1024 * 10), // 10MB

    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
    ],

    'hash_algorithm' => env('STORAGE_HASH_ALGORITHM', 'sha256'),

    'encryption' => [
        'enabled' => env('STORAGE_ENCRYPTION_ENABLED', false),
        'key' => env('STORAGE_ENCRYPTION_KEY'),
    ],
];
EOL

# Create migration
cat > database/migrations/create_file_storage_table.php << EOL
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_storage', function (Blueprint \$table) {
            \$table->id();
            \$table->string('path');
            \$table->string('disk');
            \$table->string('mime_type');
            \$table->bigInteger('size');
            \$table->string('hash')->nullable();
            \$table->json('metadata')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_storage');
    }
};
EOL

# Create base test case
cat > tests/TestCase.php << EOL
<?php

namespace zennit\Storage\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use zennit\Storage\StorageServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders(\$app)
    {
        return [
            StorageServiceProvider::class,
        ];
    }

    protected function defineEnvironment(\$app)
    {
        // Setup default database to use sqlite :memory:
        \$app['config']->set('database.default', 'testbench');
        \$app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
EOL

# Create README
cat > README.md << EOL
# Laravel Storage Management Package

A flexible and extensible storage management package for Laravel applications.

## Installation

\`\`\`bash
composer require zennit/storage
\`\`\`

## Configuration

Publish the config file:

\`\`\`bash
php artisan vendor:publish --tag=storage-config
\`\`\`

## Usage

\`\`\`php
use zennit\Storage\Facades\Storage;

// Example implementation coming soon
\`\`\`

## Testing

\`\`\`bash
composer test
\`\`\`

## License

MIT
EOL

# Create phpunit configuration
cat > phpunit.xml << EOL
<?xml version="1.0" encoding="UTF-8"?>
<phpunit
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd"
    bootstrap="vendor/autoload.php"
    colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory suffix=".php">./src</directory>
        </include>
    </coverage>
    <php>
        <env name="DB_CONNECTION" value="testing"/>
        <env name="APP_KEY" value="base64:2fl+Ktvkfl+Fuz4Qp/A75G2RTiWVA/ZoKZvp6fiiM10="/>
    </php>
</phpunit>
EOL

# Initialize git repository
git init
git add .
git commit -m "Initial commit: Setup Laravel storage package structure"

echo "Laravel storage package structure has been set up successfully!"
