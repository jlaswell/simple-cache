<?php

namespace Jlaswell\SimpleCache;

use Traversable;
use Psr\SimpleCache\CacheInterface;
use Jlaswell\SimpleCache\KeyValidation;

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

    public function get($key, $default = null)
    {
        $this->validateKey($key);

        return $this->data[$key] ?? $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->validateKey($key);
        $this->data[$key] = $value;

        return true;
    }

    public function delete($key)
    {
        $this->validateKey($key);
        unset($this->data[$key]);

        return true;
    }

    public function clear()
    {
        $this->data = [];
    }

    public function getMultiple($keys, $default = null)
    {
        $keys = $this->transformKeys($keys);

        $data = array_merge(
            array_fill_keys($keys, $default),
            array_intersect_key($this->data, array_flip($keys))
        );

        return $data;
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

        return true;
    }

    public function has($key)
    {
        $this->validateKey($key);

        return isset($this->data[$key]);
    }
}
