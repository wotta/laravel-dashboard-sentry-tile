<?php

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $table = 'sentry_issues';

    protected $guarded = [];

    protected $casts = [
        'first_seen' => 'date',
        'last_seen' => 'date',
        'meta' => 'array',
        'assigned_to' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
