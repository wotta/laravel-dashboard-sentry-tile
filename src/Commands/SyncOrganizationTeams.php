<?php

namespace Wotta\SentryTile\Commands;

use Illuminate\Console\Command;

class SyncOrganizationTeams extends Command
{
    protected $signature = 'sentry:sync:organization:teams';

    protected $description = 'Fetch the organization teams';
}
