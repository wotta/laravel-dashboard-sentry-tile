<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands\Traits;

use Wotta\SentryTile\Models\Project;

trait SyncsProjects
{
    public function syncProjects(array $projects): int
    {
        return Project::upsert(
            $projects,
            ['team_id', 'slug']
        );
    }
}
