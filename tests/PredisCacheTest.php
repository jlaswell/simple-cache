<?php

namespace Realpage\SimpleCache\Tests;

use Predis\Client;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Realpage\SimpleCache\PredisCache;

class PredisCacheTest extends TestCase implements CacheInterfaceTest
{
    use CacheInterfaceTestCases;

    public function setUp()
    {
        $this->buildPredisClient()->flushall();
    }

    public function buildCache($data = null): CacheInterface
    {
        return new PredisCache($this->buildPredisClient(), $data);
    }

    /**
     * @return \Predis\Client
     */
    private function buildPredisClient()
    {
        return new Client('tcp://127.0.0.1:6379');
    }
}
