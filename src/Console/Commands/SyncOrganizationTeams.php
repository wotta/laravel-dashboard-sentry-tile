<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands;

use Illuminate\Console\Command;
use Wotta\SentryTile\Models\Team;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Wotta\SentryTile\Console\Commands\Traits\InteractsWithOrganization;

class SyncOrganizationTeams extends Command
{
    use InteractsWithOrganization;

    protected $name = 'sentry:sync:organization:teams';

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

            return $team;
        })
        ->when($this->option('with-projects'))
        ->mapWithKeys(function ($team) {
            return [$team->id => $team['projects']];
        })
        ->each(function ($projects) {

            // Import project
//            return [
//                'slug' => '',
//                'name' => $project['name'],
//                'organization' => $project['organization']['slug'],
//            ];
        });
    }

    public function getArguments(): array
    {
        return [
            ['organization', InputArgument::OPTIONAL, 'A static organization slug'],
        ];
    }

    public function getOptions(): array
    {
        return [
            ['with-projects', null, InputOption::VALUE_NONE, 'When the team has projects import those projects.'],
        ];
    }
}
