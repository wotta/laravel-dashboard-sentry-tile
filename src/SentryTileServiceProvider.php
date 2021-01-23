<?php

namespace Wotta\SentryTile;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Wotta\SentryTile\Exceptions\NoClientTokenSet;
use Wotta\SentryTile\Console\Commands\SyncOrganizationTeams;
use Wotta\SentryTile\Console\Commands\SyncOrganizationProject;
use Wotta\SentryTile\Console\Commands\ListenForSentryIssuesCommand;

class SentryTileServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        $this->registerCommands();

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dashboard-sentry-tile'),
        ], 'dashboard-sentry-tile-views');

        $files = File::allFiles(__DIR__ . '/../migrations');

        foreach ($files as $file) {
            $filename = Str::substr($file->getFilename(), 2);

            $this->publishes([
                __DIR__ . '/../migrations/' . $file->getFilename() => $this->getMigrationFileName($filesystem, Str::before($filename, '.stub')),
            ], 'dashboard-sentry-migrations');

            sleep(1);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-sentry-tile-views');

        Livewire::component('sentry-tile', SentryTileComponent::class);
    }

    public function register(): void
    {
        Http::macro('prepareClient', function () {
            throw_if(! config('dashboard.tiles.sentry.token'), NoClientTokenSet::class);

            return $this
                ->withToken(config('dashboard.tiles.sentry.token'))
                ->baseUrl(config('dashboard.tiles.sentry.base_url', 'https://sentry.io/api/0/'));
        });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @param string $filename
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem, string $filename): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $filename) {
                return $filesystem->glob($path.'*_'.$filename);
            })->push($this->app->databasePath()."/migrations/{$timestamp}_".$filename)
            ->first();
    }

    /**
     * Register our custom commands
     *
     * @return $this
     */
    protected function registerCommands(): self
    {
        $this->commands([
            SyncOrganizationTeams::class,
            SyncOrganizationProject::class,
            ListenForSentryIssuesCommand::class,
        ]);

        return $this;
    }
}
