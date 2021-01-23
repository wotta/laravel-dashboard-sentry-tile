<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests;

use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Wotta\SentryTile\SentryTileServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Console\VendorPublishCommand;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $files = File::files(__DIR__ . '/../vendor/orchestra/testbench-core/laravel/database/migrations');

        collect($files)->each(function (\SplFileInfo $file) {
            if ($file->getFilename() === '.gitkeep') {
                return;
            }

            File::delete($file->getPathname());
        });

        Artisan::call(VendorPublishCommand::class, [
            '--tag' => 'dashboard-sentry-migrations',
            '--force' => true,
        ]);

        $this->artisan('migrate')->run();
    }

    /**
     * @param Application $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            SentryTileServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
//        $app['config']->set('database.default', 'testbench');
//        $app['config']->set('database.connections.testbench', [
//            'driver' => 'sqlite',
//            'database' => 'database.sqlite',
//            'prefix' => '',
//        ]);
    }
}
