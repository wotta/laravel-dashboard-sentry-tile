<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Models;

use Illuminate\Database\Eloquent\Model;
use Wotta\SentryTile\Tests\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issue extends Model
{
    use HasFactory;

    protected $table = 'sentry_issues';

    protected $guarded = [];

    protected $casts = [
        'first_seen' => 'datetime',
        'last_seen' => 'datetime',
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
