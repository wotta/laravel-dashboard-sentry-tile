<?php

namespace Wotta\SentryTile\Stores;

use Carbon\Carbon;
use Spatie\Dashboard\Models\Tile;
use Wotta\SentryTile\Objects\Issue;

class SentryStore
{
    private Tile $tile;

    public static function make(string $project)
    {
        return new static($project);
    }

    public function __construct(string $project)
    {
        $this->tile = Tile::firstOrCreateForName("sentry_{$project}");
    }

    public function addIssue(array $issue)
    {
        $issues = $this->tile->getData('issues') ?? [];

        array_unshift($issues, $issue);

        $issues = array_slice($issues, 0, config('dashboard.tiles.sentry.import.max_issues', 20));

        $this->tile->putData('issues', $issues);
    }

    public function addIssues(array $issues): void
    {
        if (empty($issues)) {
            return;
        }

        collect($issues)->each(fn (array $issue) => $this->addIssue($issue));
    }

    public function issues(): array
    {
        return collect($this->tile->getData('issues') ?? [])
            ->map(fn (array $issueAttributes) => new Issue($issueAttributes))
            ->reject(fn (Issue $issue) => $issue->isRejected())
            ->take(config('dashboard.tiles.sentry.import.max_issues', 20))
            ->sortByDesc(function (Issue $issue) {
                return $issue->lastSeen(Issue::FORMAT);
            })
            ->toArray();
    }
}
