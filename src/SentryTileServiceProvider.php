<?php

namespace Wotta\SentryTile;

use Livewire\Livewire;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Wotta\SentryTile\Commands\ListenForSentryIssuesCommand;

class SentryTileServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem)
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ListenForSentryIssuesCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dashboard-sentry-tile'),
        ], 'dashboard-sentry-tile-views');

        $files = File::allFiles(__DIR__ . '/../migrations');

        foreach ($files as $file) {
            $filename = Str::substr($file->getFilename(), 2);

            $this->publishes([
                __DIR__ . '/../migrations/' . $file->getFilename() => $this->getMigrationFileName($filesystem, Str::before($filename, '.stub'))
            ], 'dashboard-sentry-migrations');

            sleep(1);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-sentry-tile-views');

        Livewire::component('sentry-tile', SentryTileComponent::class);
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
}
