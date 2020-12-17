<?php

namespace Wotta\SentryTile;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Wotta\SentryTile\Exceptions\NoClientTokenSet;

class SentryClient extends Factory
{
    /**
     * @return PendingRequest
     * @throws \Throwable|NoClientTokenSet
     */
    public static function prepareClient(): PendingRequest
    {
        throw_if(! config('dashboard.tiles.sentry.token'), NoClientTokenSet::class);

        return (new static)
            ->withToken(config('dashboard.tiles.sentry.token'))
            ->baseUrl(config('dashboard.tiles.sentry.base_url', 'https://sentry.io/api/0/'));
    }
}
