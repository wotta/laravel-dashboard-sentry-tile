<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Objects;

use Webmozart\Assert\Assert;

class Assignee
{
    protected array $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function id()
    {
        Assert::keyExists($this->attributes, 'id');

        return $this->attributes['id'];
    }

    public function type(): string
    {
        Assert::keyExists($this->attributes, 'type');

        return $this->attributes['type'];
    }

    public function email(): string
    {
        Assert::keyExists($this->attributes, 'email');
        Assert::email($this->attributes['email']);

        return $this->attributes['email'];
    }
}
