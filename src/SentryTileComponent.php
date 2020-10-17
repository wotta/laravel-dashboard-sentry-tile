<?php

namespace Wotta\SentryTile;

use Livewire\Component;

class SentryTileComponent extends Component
{
    public $position;


    public function mount(string $position)
    {
        $this->position = $position;
    }


    public function render()
    {
        return view('dashboard-skeleton-tile::tile', [
            'myData' => SentryStore::make()->getData(),
            'refreshIntervalInSeconds' => config('dashboard.tiles.skeleton.refresh_interval_in_seconds') ?? 60,

        ]);
    }
}
