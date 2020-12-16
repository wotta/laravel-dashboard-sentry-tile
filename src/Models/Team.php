<?php

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $table = 'sentry_teams';

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
