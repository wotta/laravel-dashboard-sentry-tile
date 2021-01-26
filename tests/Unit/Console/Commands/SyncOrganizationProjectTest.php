<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Console\Commands;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Tests\TestCase;
use Wotta\SentryTile\Tests\Traits\InteractWithFixture;
use Wotta\SentryTile\Tests\Unit\Traits\InteractWithSentry;
use Wotta\SentryTile\Tests\Unit\Traits\InteractWithOrganizationConfig;

class SyncOrganizationProjectTest extends TestCase
{
    use InteractWithSentry,
        InteractWithFixture,
        InteractWithOrganizationConfig;

    public function test_command_displays_information_that_is_stored(): void
    {
        $this->setOrganizationConfig();

        $url = sprintf('https://sentry.io/api/0/projects/');

        Http::fake([
            $url => Http::response($this->getFixture('projects.json'), Response::HTTP_OK),
        ]);

        $this->artisan('sentry:sync:organization:projects')
            ->expectsOutput('Syncing projects for organization')
            ->expectsOutput('Created project: The Spoiled Yoghurt')
            ->expectsOutput('Created project: Prime Mover')
            ->expectsOutput('Created project: Pump Station')
            ->assertExitCode(0);

        $this->assertDatabaseCount('sentry_projects', 3);
    }
}
