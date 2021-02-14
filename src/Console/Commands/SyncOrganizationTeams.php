<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Wotta\SentryTile\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Wotta\SentryTile\Console\Commands\Traits\SyncsProjects;
use Wotta\SentryTile\Console\Commands\Traits\InteractsWithOrganization;

class SyncOrganizationTeams extends Command
{
    use SyncsProjects;
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

        collect($teams)->mapWithKeys(function ($team) {
            /** @var Team $team */
            $teamModel = Team::updateOrCreate(
                ['slug' => $team['slug']],
                ['name' => $team['name']]
            );

            $infoText = $teamModel->wasRecentlyCreated ? 'Created' : 'Updated';

            $this->comment(sprintf('%s team: %s', $infoText, $teamModel->name));

            return [$teamModel->id => $team];
        })
        ->when($this->option('with-projects'), function (Collection $collection) {
            return $collection->mapWithKeys(function ($teamData, $teamId) {
                // Get the projects
                $teamProjects = Arr::get($teamData, 'projects', []);

                $projects = [];

                foreach ($teamProjects as $project) {
                    $projects[] = Arr::only($project, ['name', 'slug']) +
                        [
                            'organization' => config('dashboard.tiles.sentry.organization'),
                            'team_id' => $teamId,
                        ];
                }

                // convert array to be used with the projects import.
                return [$teamId => $projects];
            })
                ->flatten(1)
                ->tap(function ($collection) {
                    $this->syncProjects($collection->toArray());

                    $this->info('Imported projects');
                });
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
