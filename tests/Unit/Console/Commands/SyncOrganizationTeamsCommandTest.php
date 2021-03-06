<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Console\Commands;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Tests\TestCase;
use Wotta\SentryTile\Tests\Traits\InteractWithFixture;
use Wotta\SentryTile\Tests\Unit\Traits\InteractWithSentry;
use Wotta\SentryTile\Tests\Unit\Traits\InteractWithOrganizationConfig;

class SyncOrganizationTeamsCommandTest extends TestCase
{
    use InteractWithSentry,
        InteractWithFixture,
        InteractWithOrganizationConfig;

    public function test_command_display_information_that_is_stored(): void
    {
        $this->setOrganizationConfig();

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

    public function test_can_import_projects_for_the_teams(): void
    {
        $this->setOrganizationConfig();

        $url = sprintf('https://sentry.io/api/0/organizations/%s/teams/', $this->organization);

        Http::fake([
            $url => Http::response($this->getFixture('teams_with_projects.json'), Response::HTTP_OK),
        ]);

        $this->artisan('sentry:sync:organization:teams', ['--with-projects' => true])
            ->expectsOutput('Syncing teams for organization')
            ->expectsOutput('Created team: Ancient Gabelers')
            ->expectsOutput('Created team: Powerful Abolitionist')
            ->expectsOutput('Imported projects')
            ->assertExitCode(0);
    }
}
