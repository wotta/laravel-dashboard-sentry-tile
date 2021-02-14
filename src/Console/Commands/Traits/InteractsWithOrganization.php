<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Console\Commands\Traits;

use Illuminate\Http\Response;
use Wotta\SentryTile\Exceptions\NoOrganizationSet;

trait InteractsWithOrganization
{
    public function getOrganization(): string
    {
        $organization = $this->argument('organization') ?? config('dashboard.tiles.sentry.organization');

        abort_if(! $organization, Response::HTTP_INTERNAL_SERVER_ERROR, NoOrganizationSet::class);

        return $organization;
    }
}
