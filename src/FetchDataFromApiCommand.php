<?php

namespace Wotta\SentryTile;

use Illuminate\Console\Command;

class FetchDataFromApiCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-from-sentry-api';

    protected $description = 'Fetch data for tile';

    public function handle(VeloApi $velo)
    {
        $this->info('Fetching Velo stations...');

        $myData = Http::get($endpoint)->json();

        SentryStore::make()->setData($myData);

        $this->info('All done!');
    }
}
