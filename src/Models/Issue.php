<?php

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wotta\SentryTile\Tests\Factories\IssueFactory;

class Issue extends Model
{
    use HasFactory;

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

    protected static function newFactory(): Factory
    {
        return IssueFactory::new();
    }
}
