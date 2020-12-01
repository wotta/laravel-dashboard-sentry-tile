<?php

namespace Wotta\SentryTile\Objects;

use Webmozart\Assert\Assert;

class Project
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function id(): string
    {
        Assert::keyExists($this->attributes, 'id');

        return $this->attributes['id'];
    }

    public function name(): string
    {
        Assert::keyExists($this->attributes, 'name');

        return $this->attributes['name'];
    }

    public function slug(): string
    {
        Assert::keyExists($this->attributes, 'slug');

        return $this->attributes['slug'];
    }

    public function platform(): string
    {
        Assert::keyExists($this->attributes, 'platform');

        return $this->attributes['platform'];
    }
}
