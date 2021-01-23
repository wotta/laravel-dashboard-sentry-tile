<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Commands;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Tests\TestCase;
use Wotta\SentryTile\Tests\Traits\InteractWithFixture;

class SyncOrganizationTeamsCommandTest extends TestCase
{
    use InteractWithFixture;

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

    public function test_command_display_information_that_is_stored(): void
    {
        $this->app['config']->set('dashboard.tiles.sentry.organization', $this->organization);

        $url = sprintf('https://sentry.io/api/0/organizations/%s/teams/', $this->organization);

        Http::fake([
            $url => Http::response($this->getFixture('teams_without_projects.json'), Response::HTTP_OK),
        ]);

        $this->artisan('sentry:sync:organization:teams')
            ->expectsOutput('Syncing teams for organization')
            ->expectsOutput('Created team: Ancient Gabelers')
            ->expectsOutput('Created team: Powerful Abolitionist')
            ->assertExitCode(0);
    }
}
