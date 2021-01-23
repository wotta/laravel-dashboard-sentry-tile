<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands;

use Illuminate\Console\Command;
use Wotta\SentryTile\Models\Team;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Console\Commands\Traits\InteractsWithOrganization;

class SyncOrganizationTeams extends Command
{
    use InteractsWithOrganization;

    protected $signature = 'sentry:sync:organization:teams {organization? : A static organization slug}';

    protected $description = 'Fetch the organization teams';

    public function handle(): void
    {
        $teams = Http::prepareClient()->get(
            sprintf(
                'organizations/%s/teams/',
                $this->getOrganization()
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

            $infoText = $team->wasRecentlyCreated ? 'Created' : 'Updated';

            $this->comment(sprintf('%s team: %s', $infoText, $team['name']));
        });
    }
}
