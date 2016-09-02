<?php

namespace Realpage\SimpleCache;

use Traversable;
use Predis\Client;
use Psr\SimpleCache\CacheInterface;
use Realpage\SimpleCache\KeyValidation;

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

    public function get($key)
    {
        return $this->client->get($this->validateKey($key));
    }

    public function set($key, $value, $ttl = null)
    {
        if (is_null($value)) {
            $this->client->del($this->validateKey($key));

            return false;
        }

        return $this->client->set($this->validateKey($key), $value)->getPayload() === 'OK';
    }

    public function delete($key)
    {
        $this->client->del($this->validateKey($key));
    }

    public function clear()
    {
        $this->client->flushall();
    }

    public function getMultiple($keys)
    {
        $keys   = $this->transformKeys($keys);
        $values = $this->client->mget($keys);

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
        $this->client->del($this->transformKeys($keys));
    }

    public function exists($key)
    {
        return !is_null($this->client->get($this->validateKey($key)));
    }
}
