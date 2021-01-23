<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Console\Commands;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Tests\TestCase;
use Wotta\SentryTile\Tests\Traits\InteractWithFixture;
use Wotta\SentryTile\Tests\Unit\Traits\InteractWithSentry;

class SyncOrganizationTeamsCommandTest extends TestCase
{
    use InteractWithSentry,
        InteractWithFixture;

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
