<?php

namespace Wotta\SentryTile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Wotta\SentryTile\Models\Project;
use Wotta\SentryTile\Objects\Issue;

class SentryTileComponent extends Component
{
    /** @var int */
    public $refreshIntervalInSeconds;

    /** @var string */
    public $position;

    /** @var string|null */
    public $title;

    /** @var ?string */
    public $projectName;

    /** @var bool */
    public $showMeta;

    public function mount(string $position, ?string $projectName = null, ?bool $showMeta = false, ?string $title = null, ?int $refreshIntervalInSeconds = 900)
    {
        $this->position = $position;
        $this->projectName = $projectName;
        $this->showMeta = $showMeta;
        $this->title = $title;
        $this->refreshIntervalInSeconds = $refreshIntervalInSeconds;
    }

    public function render(): View
    {
        $issues = $this->getIssues();

        return view('dashboard-sentry-tile-views::tile', [
            'issues' => $issues ?? [],
        ]);
    }

    protected function getIssues(): Collection
    {
        $project = null;

        if (! $this->projectName) {
            return $this->getLatestProjectIssues();
        }

        if ($this->projectName) {
            $project = Project::where('name', $this->projectName)->orWhere('slug', $this->projectName)->first();
        }

        if ($this->projectName && $project) {
            return $project->issues->sortByDesc('last_seen')->sortByDesc('created_at')->load('project')->mapInto(Issue::class);
        }

        return collect([]);
    }

    protected function getLatestProjectIssues(): Collection
    {
        if (class_exists('App\Actions\GetLatestProjectsIssues')) {
            return (new App\Actions\GetLatestProjectsIssues())->handle();
        }

        return (new GetLatestProjectsIssues())->handle();
    }
}
