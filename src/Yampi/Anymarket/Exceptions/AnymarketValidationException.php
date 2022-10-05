<?php

namespace Yampi\Anymarket\Exceptions;

use Exception;

class AnymarketValidationException extends Exception
{
    public function __construct($message, $code, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
