<?php

namespace Wotta\SentryTile\Tests;

use Livewire\LivewireServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Wotta\SentryTile\SentryTileServiceProvider;
use Illuminate\Foundation\Console\VendorPublishCommand;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call(VendorPublishCommand::class, [
            '--tag' => 'dashboard-sentry-migrations'
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
