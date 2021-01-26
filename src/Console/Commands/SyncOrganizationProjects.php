<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Models\Project;

class SyncOrganizationProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sentry:sync:organization:projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the organization projects';

    public function handle(): void
    {
        $projects = Http::prepareClient()->get('projects/');

        $this->info('Syncing projects for organization');

        $projects = json_decode($projects->body(), true);

        collect($projects)->each(function ($project) {
            $project = Project::updateOrCreate(
                ['slug' => $project['slug']],
                [
                    'name' => $project['name'],
                    'organization' => $project['organization']['slug'],
                ]
            );

            $infoText = $project->wasRecentlyCreated ? 'Created' : 'Updated';

            $this->comment(sprintf('%s project: %s', $infoText, $project['name']));
        });
    }
}
