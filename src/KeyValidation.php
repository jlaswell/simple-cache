<?php

namespace Jlaswell\SimpleCache;

use Traversable;
use Jlaswell\SimpleCache\InvalidKeyException;

trait KeyValidation
{
    public function validateKey($key) : string
    {
        if (!is_string($key) || strpbrk($key, '{}()/\@:') || strlen($key) < 1) {
            throw new InvalidKeyException();
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

        // @todo May need to adjust this exception type
        throw new InvalidKeyException(
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
