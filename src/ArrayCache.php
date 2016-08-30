<?php

namespace Realpage\SimpleCache;

use Traversable;
use InvalidArgumentException;
use Psr\Simplecache\CacheInterface;

class ArrayCache implements CacheInterface
{
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
        $keys = $this->transformKeys($items);
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

    // TODO: Move into a CachedItem class eventually
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
            "Cache keys must be of type string and must not contain any of the characters '{}()/@:'"
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

    private function validateKey($key)
    {
        if ($this->keyFailsRequirements($key)) {
            throw new InvalidArgumentException(
                "Cache keys must be of type string and must not contain any of the characters '{}()/@:'"
            );
        }
    }

    private function keyFailsRequirements($key)
    {
        return !is_string($key) || strpbrk($key, '{}()/\@:') || strlen($key) === 0;
    }
}
