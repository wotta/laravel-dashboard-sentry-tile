<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Actions;

use Illuminate\Support\Collection;
use Wotta\SentryTile\Models\Issue;
use Wotta\SentryTile\Objects\Issue as IssueObject;

class GetLatestProjectsIssues
{
    public function handle(int $limit = 20): Collection
    {
        $issues = Issue::with('project')->limit($limit)->orderByDesc('last_seen');

        return $issues->get()->mapInto(IssueObject::class);
    }
}
