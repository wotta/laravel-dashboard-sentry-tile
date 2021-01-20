<?php

namespace Wotta\SentryTile\Exceptions;

use Exception;

class NoOrganizationSet extends Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(
            'No organization set for sentry',
            $code,
            $previous
        );
    }
}
