<?php

namespace Wotta\SentryTile;

use Illuminate\Contracts\View\View;
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
        if ($this->projectName) {
            /** @var Project $project */
            $project = Project::where('name', $this->projectName)->orWhere('slug', $this->projectName)->first();
        }

        if (! $this->projectName) {
            $issues = $this->getLatestProjectIssues();
        }

        return view('dashboard-sentry-tile-views::tile', [
            'issues' => $this->projectName ? $project->issues->sortByDesc('last_seen')->load('project')->mapInto(Issue::class) ?? [] : $issues,
        ]);
    }

    protected function getLatestProjectIssues()
    {
        if (class_exists('App\Actions\GetLatestProjectsIssues')) {
            return (new App\Actions\GetLatestProjectsIssues())->handle();
        }

        return (new GetLatestProjectsIssues())->handle();
    }
}
