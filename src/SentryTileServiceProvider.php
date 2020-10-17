<?php

namespace Wotta\SentryTile;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SentryTileServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchDataFromApiCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/dashboard-sentry-tile'),
        ], 'dashboard-sentry-tile-views');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'dashboard-sentry-tile');

        Livewire::component('sentry-tile', SentryTileComponent::class);
    }
}
