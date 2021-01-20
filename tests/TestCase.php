<?php

namespace Wotta\SentryTile\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Wotta\SentryTile\SentryTileServiceProvider;

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

        dump(Schema::hasTable('sentry_issues'));
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
