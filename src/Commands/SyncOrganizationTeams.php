<?php

namespace Wotta\SentryTile\Commands;

use Illuminate\Console\Command;
use Wotta\SentryTile\Exceptions\NoOrganizationSet;
use Wotta\SentryTile\SentryClient;

class SyncOrganizationTeams extends Command
{
    protected $signature = 'sentry:sync:organization:teams {organization? : A static organization slug}';

    protected $description = 'Fetch the organization teams';

    public function handle(): void
    {
        $organization = $this->argument('organization') ?? config('dashboard.tiles.sentry.organization');

        abort_if(! $organization, NoOrganizationSet::class);

        $teams = SentryClient::prepareClient()->get(
            sprintf(
                'organizations/%s/team',
                $organization
            )
        );
    }
}
