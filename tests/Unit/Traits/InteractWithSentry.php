<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Traits;

trait InteractWithSentry
{
    private string $organization;

    /**
     * All tests tests that should be run here need to have an api token set.
     * Testing the command with no api token is not needed because the
     * client test already makes sure the correct error is thrown.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->organization = 'test-organization';

        $this->app['config']->set('dashboard.tiles.sentry.token', 'test_api_token');
        $this->app['config']->set('dashboard.tiles.sentry.base_url', 'https://sentry.io/api/0/');
    }
}
