<?php

namespace Wotta\SentryTile\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\VendorPublishCommand;
use Illuminate\Support\Facades\Artisan;
use Livewire\LivewireServiceProvider;
use Wotta\SentryTile\SentryTileServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call(VendorPublishCommand::class, [
            '--tag' => 'dashboard-sentry-migrations',
        ]);

        $this->artisan('migrate', [
            '--database' => 'testbench',
        ]);
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
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
