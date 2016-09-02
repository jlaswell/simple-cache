<?php

namespace Realpage\SimpleCache;

use Traversable;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use Realpage\SimpleCache\KeyValidation;

class ArrayCache implements CacheInterface
{
    use KeyValidation;

    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = array_combine(
            $this->transformKeys(array_keys($data)),
            array_values($data)
        );
    }

    public function get($key)
    {
        $this->validateKey($key);

        return $this->data[$key] ?? null;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->validateKey($key);
        $this->data[$key] = $value;

        if (is_null($value)) {
            return false;
        }

        return true;
    }

    public function delete($key)
    {
        $this->validateKey($key);
        unset($this->data[$key]);
    }

    public function clear()
    {
        $this->data = [];
    }

    public function getMultiple($keys)
    {
        $keys = $this->transformKeys($keys);

        return array_merge(
            array_fill_keys($keys, null),
            array_intersect_key($this->data, array_flip($keys))
        );
    }

    public function setMultiple($items, $ttl = null)
    {
        $this->transformKeys($items);
        foreach ($items as $key => $value) {
            $this->data[$key] = $value;
        }

        return true;
    }

    public function deleteMultiple($keys)
    {
        $keys       = $this->transformKeys($keys);
        $this->data = array_diff_key($this->data, array_flip($keys));
    }

    public function exists($key)
    {
        $this->validateKey($key);

        return isset($this->data[$key]);
    }
}
