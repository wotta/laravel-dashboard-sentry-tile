<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit\Traits;

trait InteractWithOrganizationConfig
{
    protected function setOrganizationConfig(?string $organization = null)
    {
        if (! $organization) {
            $organization = $this->organization;
        }

        $this->app['config']->set('dashboard.tiles.sentry.organization', $organization);
    }
}
