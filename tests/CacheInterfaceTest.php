<?php

namespace Jlaswell\SimpleCache\Tests;

use Psr\SimpleCache\CacheInterface;

interface CacheInterfaceTest
{
    public function buildCache($data = null): CacheInterface;
}
