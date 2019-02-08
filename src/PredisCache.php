<?php

namespace Jlaswell\SimpleCache;

use Traversable;
use Predis\Client;
use Psr\SimpleCache\CacheInterface;
use Jlaswell\SimpleCache\KeyValidation;

class PredisCache implements CacheInterface
{
    use KeyValidation;

    private $client;

    public function __construct(Client $client, $data = null)
    {
        $this->client = $client;
        if (!is_null($data)) {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
    }

    public function get($key, $default = null)
    {
        return $this->client->get($this->validateKey($key)) ?? $default;
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->client->set($this->validateKey($key), $value)
            ->getPayload() === 'OK';
    }

    public function delete($key)
    {
        $removedKeys = $this->client->del($this->validateKey($key));

        return $removedKeys === 1 || $removedKeys === 0;
    }

    public function clear()
    {
        $this->client->flushall();
    }

    public function getMultiple($keys, $default = null)
    {
        $keys   = $this->transformKeys($keys);
        $values = $this->client->mget($keys);

        if (!is_null($default)) {
            foreach ($values as $key => $value) {
                is_null($value) ? $values[$key] = $default : null;
            }
        }

        return array_combine($keys, $values);
    }

    public function setMultiple($items, $ttl = null)
    {
        $this->transformKeys($items);
        if ($items instanceof Traversable) {
            $items = iterator_to_array($items, true);
        }

        return $this->client->mset($items)->getPayload() === 'OK';
    }

    public function deleteMultiple($keys)
    {
        return $this->client->del($this->transformKeys($keys)) <= sizeof($keys);
    }

    public function has($key)
    {
        return !is_null($this->client->get($this->validateKey($key)));
    }
}
