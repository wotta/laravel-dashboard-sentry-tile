<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Exceptions;

use Exception;
use Throwable;

class NoClientTokenSet extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(
            "No sentry api token is set while trying to connect to sentry.\n
            Please visit: https://sentry.io/settings/account/api/",
            $code,
            $previous
        );
    }
}
