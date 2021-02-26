<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Contracts\View\View;
use Wotta\SentryTile\Actions\GetLatestTeamIssues;

class SentryTeamIssuesComponent extends BaseSentryComponent
{
    public ?string $team;

    public function mount(string $position, ?bool $showMeta = false, ?string $title = null, ?int $refreshIntervalInSeconds = 15, ...$attributes): void
    {
        parent::mount($position, $showMeta, $title, $refreshIntervalInSeconds);

        $this->team = Arr::get(Arr::get($attributes, 0, []), 'team');
    }

    public function render(): View
    {
        return view('dashboard-sentry-tile-views::teams.tile', [
            'team' => $this->team,
        ]);
    }

    public function getIssuesProperty()
    {
        if (! $this->team) {
            return [];
        }

        if (class_exists('App\Actions\GetLatestProjectsIssues')) {
            return (new App\Actions\GetTeamIssues())->handle();
        }

        return (new GetLatestTeamIssues())->handle($this->team);
    }
}
