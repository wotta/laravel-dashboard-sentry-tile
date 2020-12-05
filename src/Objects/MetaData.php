<?php

namespace Wotta\SentryTile\Objects;

class MetaData
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function title(): ?string
    {
        return $this->attributes['title'] ?? null;
    }

    public function method(): ?string
    {
        return $this->attributes['function'] ?? null;
    }

    public function type(): ?string
    {
        return $this->attributes['type'] ?? null;
    }

    public function value(): ?string
    {
        return $this->attributes['value'] ?? null;
    }

    public function filename(): ?string
    {
        return $this->attributes['filename'] ?? null;
    }
}
