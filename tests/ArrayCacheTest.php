<?php

namespace Jlaswell\SimpleCache\Tests;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Jlaswell\SimpleCache\ArrayCache;

class ArrayCacheTest extends TestCase implements CacheInterfaceTest
{
    use CacheInterfaceTestCases;

    public function buildCache($data = null): CacheInterface
    {
        return new ArrayCache($data ?? []);
    }
}
