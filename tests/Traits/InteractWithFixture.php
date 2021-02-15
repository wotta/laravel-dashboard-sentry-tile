<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Traits;

use Illuminate\Support\Facades\File;

trait InteractWithFixture
{
    protected string $fixturePath = __DIR__ . '/../Fixtures/';

    /**
     * @param string $path
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFixture(string $path): string
    {
        return File::get($this->fixturePath . $path);
    }
}
