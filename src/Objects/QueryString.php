<?php

namespace Wotta\SentryTile\Objects;

class QueryString
{
    /** @var \Illuminate\Support\Collection */
    protected $queryString;

    public static function make()
    {
        return new static();
    }

    public function __construct()
    {
        $this->queryString = collect([]);
    }

    public function addQuery(string $parameter, string $value, bool $append = false)
    {
        if ($append) {
            $collectionValue = $this->queryString->get($parameter);

            $this->queryString->put($parameter, $collectionValue . ' ' . $value);

            return $this;
        }

        $this->queryString->put($parameter, $value);

        return $this;
    }

    public function getQueryString()
    {
        return '?' . http_build_query($this->queryString->toArray());
    }
}
