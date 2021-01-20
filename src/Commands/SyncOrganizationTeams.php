<?php

namespace Wotta\SentryTile\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Exceptions\NoOrganizationSet;
use Wotta\SentryTile\Models\Team;

class SyncOrganizationTeams extends Command
{
    protected $signature = 'sentry:sync:organization:teams {organization? : A static organization slug}';

    protected $description = 'Fetch the organization teams';

    public function handle(): void
    {
        $organization = $this->argument('organization') ?? config('dashboard.tiles.sentry.organization');

        abort_if(! $organization, NoOrganizationSet::class);

        $teams = Http::prepareClient()->get(
            sprintf(
                'organizations/%s/teams/',
                $organization
            )
        );

        $this->info('Syncing teams for organization');

        $teams = json_decode($teams->body(), true);

        collect($teams)->each(function ($team) {
            /** @var Team $team */
            $team = Team::updateOrCreate(
                ['slug' => $team['slug']],
                ['name' => $team['name']]
            );

            $infoText = $team->wasRecentlyCreated ? 'Created': 'Updated';

            $this->comment(sprintf('%s team: %s', $infoText, $team['name']));
        });
    }
}
