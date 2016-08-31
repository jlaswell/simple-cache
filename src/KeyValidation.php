<?php

namespace Realpage\SimpleCache;

use InvalidArgumentException;
use UnexpectedValueException;

trait KeyValidation
{
    public function validateKey($key): string
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                "Cache keys must be a string"
            );
        } elseif (strpbrk($key, '{}()/\@:') || strlen($key) === 0) {
            throw new UnexpectedValueException(
                "Cache keys must contain at least one character and not contain any of the characters '{}()/@:'"
            );
        }

        return $key;
    }
}
