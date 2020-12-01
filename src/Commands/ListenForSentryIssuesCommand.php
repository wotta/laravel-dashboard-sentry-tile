<?php

namespace Wotta\SentryTile\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Models\Project;
use Wotta\SentryTile\Objects\QueryString;

class ListenForSentryIssuesCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-from-sentry-api';

    protected $description = 'Fetch sentry data for tile';

    public function handle()
    {
        if (! config('dashboard.tiles.sentry.token')) {
            throw new \Exception('Cannot connect to sentry without an token. Please visit: https://sentry.io/api');
        }

        $this->info('Fetching sentry data...');

        $projects = $this->client()
            ->get('projects/');

        $responseData = json_decode($projects->body(), true);

        collect($responseData)
            ->map(function ($project) {
                return Project::updateOrCreate(
                    ['slug' => $project['slug']],
                    [
                        'name' => $project['name'],
                        'organization' => $project['organization']['slug'],
                    ]
                );
            })->each(function (Project $project) {
                $issues = $this->client()->get(
                    sprintf(
                        'projects/%s/%s/issues/%s',
                        $project->organization,
                        $project->slug,
                        $this->buildQueryString($project->slug)
                    )
                );

                $projectIssues = collect(json_decode($issues->body(), true));

                if (empty($projectIssues)) {
                    return $project;
                }
//
//                if ($project->slug === 'vezwolle') {
//                    dd($projectIssues);
//                }

                return $projectIssues->map(function (array $issue) use ($project) {
                    return $project->issues()->updateOrCreate(
                        [
                            'external_id' => $issue['id'],
                        ],
                        [
                            'title' => $issue['title'],
                            'status' => $issue['status'],
                            'type' => $issue['type'],
                            'level' => $issue['level'],
                            'first_seen' => $issue['firstSeen'],
                            'last_seen' => $issue['lastSeen'],
                            'permalink' => $issue['permalink'],
                            'meta' => $issue['metadata'],
                            'assigned_to' => $issue['assignedTo'],
                        ]
                    );
                });
            });

        $this->info('All done!');
    }

    protected function client()
    {
        return Http::withToken(
            config('dashboard.tiles.sentry.token'),
        )->baseUrl('https://sentry.io/api/0/');
    }

    protected function buildQueryString(?string $projectSlug = null)
    {
        $queryString = QueryString::make()
            ->addQuery('query', 'is:unresolved');

        if (config("dashboard.tiles.sentry.production_only")) {
            $queryString->addQuery('environment', 'production');
        }


        if ($projectSlug) {
            foreach (config("dashboard.tiles.sentry.{$projectSlug}.issue_options", []) as $option) {
                if (! is_array($option)) {
                    $queryString->addQuery('query', $option, true);

                    continue;
                }

                /**
                 * Get the key name from the option array.
                 * Get the value from the option array.
                 */
                $queryString->addQuery($option, $optionValue);
            }
        }

        return $queryString->getQueryString();
    }
}
