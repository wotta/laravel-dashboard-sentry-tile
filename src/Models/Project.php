<?php

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'sentry_projects';

    protected $guarded = [];

    protected $with = [
        'issues',
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
