<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Http\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

abstract class BaseSentryComponent extends Component
{
    /** @var int */
    public $refreshIntervalInSeconds;

    /** @var string */
    public $position;

    /** @var string|null */
    public $title;

    /** @var bool */
    public $showMeta;

    public function mount(string $position, ?bool $showMeta = false, ?string $title = null, ?int $refreshIntervalInSeconds = 15, ...$attributes): void
    {
        $this->title = $title;
        $this->position = $position;
        $this->showMeta = $showMeta;
        $this->refreshIntervalInSeconds = $refreshIntervalInSeconds;
    }

    abstract public function render(): View;

    abstract public function getIssuesProperty();
}
