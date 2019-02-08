<?php

namespace Jlaswell\SimpleCache;

use Exception;
use Psr\SimpleCache\InvalidArgumentException;

class InvalidKeyException extends Exception implements InvalidArgumentException
{
    function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $message = $message ?? "Cache keys must be a string and container at least one character and not contain any of the characters '{}()/@:'";
        parent::__construct($message, $code, $previous);
    }
}
