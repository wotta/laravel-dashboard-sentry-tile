<?php

namespace Wotta\SentryTile;

use Wotta\SentryTile\Models\Issue;
use Illuminate\Support\Collection;
use Wotta\SentryTile\Objects\Issue as IssueObject;

class GetLatestProjectsIssues
{
    public function handle(): Collection
    {
        $issues = Issue::with('project')->limit(20)->orderByDesc('last_seen');

        return $issues->get()->mapInto(IssueObject::class);
    }
}
