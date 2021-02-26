<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Contracts\View\View;
use Wotta\SentryTile\Objects\Issue;
use Wotta\SentryTile\Models\Project;
use Wotta\SentryTile\Actions\GetLatestProjectsIssues;

class SentryTileComponent extends BaseSentryComponent
{
    public ?string $projectName;

    public function mount(string $position, ?bool $showMeta = false, ?string $title = null, ?int $refreshIntervalInSeconds = 900, ...$attributes): void
    {
        parent::mount($position, $showMeta, $title, $refreshIntervalInSeconds);

        $this->projectName = Arr::get(Arr::get($attributes, 0, []), 'project_name');
    }

    public function render(): View
    {
        return view('dashboard-sentry-tile-views::tile', [
            'projectName' => $this->projectName,
        ]);
    }

    public function getIssuesProperty()
    {
        if (! $this->projectName && class_exists('App\Actions\GetLatestProjectsIssues')) {
            return (new App\Actions\GetLatestProjectsIssues())->handle();
        }

        if (! $this->projectName) {
            return (new GetLatestProjectsIssues())->handle();
        }

        if ($this->projectName) {
            $project = Project::where('name', $this->projectName)->orWhere('slug', $this->projectName)->first();
        }

        if ($this->projectName && $project) {
            return $project->issues->sortByDesc('last_seen')->sortByDesc('created_at')->load('project')->mapInto(Issue::class);
        }

        return collect([]);
    }
}
