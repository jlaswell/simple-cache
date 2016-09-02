<?php

namespace Realpage\SimpleCache;

use Traversable;
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

    private function transformKeys($keys)
    {
        if ($keys instanceof Traversable) {
            return $this->transformTraversableKeys($keys);
        } elseif (is_array($keys)) {
            foreach ($keys as $key) {
                $this->validateKey($key);
            }
            return $keys;
        }

        throw new InvalidArgumentException(
            "Cannot call getMultiple with a non array or non Traversable type"
        );
    }

    private function transformTraversableKeys($keys)
    {
        $tempKeys = [];
        foreach ($keys as $key => $value) {
            $this->validateKey($key);
            $tempKeys[] = $key;
        }

        return $tempKeys;
    }
}
