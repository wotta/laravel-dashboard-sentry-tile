<?php

namespace Wotta\SentryTile\Console\Commands\Traits;

use Wotta\SentryTile\Exceptions\NoOrganizationSet;

trait InteractsWithOrganization
{
    public function getOrganization(): string
    {
        $organization = $this->argument('organization') ?? config('dashboard.tiles.sentry.organization');

        abort_if(! $organization, NoOrganizationSet::class);

        return $organization;
    }
}
