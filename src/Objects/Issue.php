<?php

namespace Wotta\SentryTile\Objects;

use Carbon\Carbon;
use Webmozart\Assert\Assert;

class Issue
{
    public const FORMAT = 'Y-m-d H:i:s';

    public const STATUSES = [
        self::RESOLVED,
        self::UNRESOLVED,
        self::IGNORED,
    ];

    public const RESOLVED = 'resolved';
    public const UNRESOLVED = 'unresolved';
    public const IGNORED = 'ignored';

    protected array $attributes;

    public function __construct(\Wotta\SentryTile\Models\Issue $model)
    {
        $this->attributes = array_merge($model->attributesToArray(), $model->relationsToArray());
    }

    public function id(): string
    {
        Assert::keyExists($this->attributes, 'external_id');

        return $this->attributes['id'];
    }

    public function title(): string
    {
        Assert::keyExists($this->attributes, 'title');

        return $this->attributes['title'];
    }

    public function status(): string
    {
        Assert::keyExists($this->attributes, 'status');
        Assert::inArray($this->attributes['status'], self::STATUSES);

        return $this->attributes['status'];
    }

    public function type(): string
    {
        Assert::keyExists($this->attributes, 'type');

        return $this->attributes['type'];
    }

    public function level(): string
    {
        Assert::keyExists($this->attributes, 'level');

        return $this->attributes['level'];
    }

    public function logger(): string
    {
        Assert::keyExists($this->attributes, 'logger');

        return $this->attributes['logger'];
    }

    public function firstSeen(string $format = null): string
    {
        Assert::keyExists($this->attributes, 'first_seen');

        return $format ?
            Carbon::parse($this->attributes['first_seen'])->format($format) :
            Carbon::parse($this->attributes['first_seen'])->diffForHumans();
    }

    public function lastSeen(string $format = null): string
    {
        Assert::keyExists($this->attributes, 'last_seen');

        return $format ?
            Carbon::parse($this->attributes['last_seen'])->format($format) :
            Carbon::parse($this->attributes['last_seen'])->diffForHumans();
    }

    public function permalink(): string
    {
        Assert::keyExists($this->attributes, 'permalink');

        return $this->attributes['permalink'];
    }

    public function meta(): MetaData
    {
        Assert::keyExists($this->attributes, 'meta');

        return new MetaData($this->attributes['meta']);
    }

    public function assignedTo(): ?Assignee
    {
        Assert::keyExists($this->attributes, 'assignedTo');

        if (! $this->attributes['assignedTo']) {
            return null;
        }

        return new Assignee($this->attributes['assignedTo']);
    }

    public function project(): ?Project
    {
        return new Project($this->attributes['project']);
    }

    public function isRejected(): bool
    {
        return $this->status() === 'resolved';
    }
}
