<?php

declare(strict_types=1);

namespace Wotta\SentryTile;

use Illuminate\Support\Collection;
use Wotta\SentryTile\Models\Issue;
use Wotta\SentryTile\Objects\Issue as IssueObject;

class GetLatestProjectsIssues
{
    public function handle(): Collection
    {
        $issues = Issue::with('project')->limit(20)->orderByDesc('last_seen');

        return $issues->get()->mapInto(IssueObject::class);
    }
}
