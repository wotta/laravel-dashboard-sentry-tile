<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'sentry_projects';

    protected $guarded = [];

    protected $with = [
        'issues',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}
