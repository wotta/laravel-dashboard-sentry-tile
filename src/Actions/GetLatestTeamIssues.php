<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Actions;

use Illuminate\Support\Collection;
use Wotta\SentryTile\Models\Issue;
use Wotta\SentryTile\Objects\Issue as IssueObject;

class GetLatestTeamIssues
{
    public function handle(string $team, int $limit = 20): Collection
    {
        $issues = Issue::with('project.team')
            ->whereHas('project.team', function ($query) use ($team) {
                $query->where('slug', $team);
            })
            ->limit($limit)
            ->orderByDesc('last_seen');

        return $issues->get()->mapInto(IssueObject::class);
    }
}
