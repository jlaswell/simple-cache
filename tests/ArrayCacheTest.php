<?php

namespace Realpage\SimpleCache\Tests;

use ArrayObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Realpage\SimpleCache\ArrayCache;

class ArrayCacheTest extends TestCase
{
    public function testCacheCanBeSetOnInstantiation()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = new ArrayCache($data);

        $this->assertEquals($data['key1'], $cache->get('key1'));
        $this->assertEquals($data['key2'], $cache->get('key2'));
    }

    public function testGetsAndSetsSimpleDataBasedOnKey()
    {
        $cache = new ArrayCache();

        $this->assertNull($cache->get('key1'));
        $this->assertTrue($cache->set('key1', 'value1'));
        $this->assertEquals('value1', $cache->get('key1'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetUnlessKeyIsString()
    {
        (new ArrayCache())->get(1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetKeyWithInvalidCharacter()
    {
        (new ArrayCache())->get(':');
    }

    public function testGetsMultipleValuesBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $keys  = new ArrayObject(['key1' => '', 'key2' => '']);
        $cache = new ArrayCache($data);

        $this->assertEquals($data, $cache->getMultiple(['key1', 'key2']));
        $this->assertEquals($data, $cache->getMultiple($keys));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetMultipleValuesIfAnyKeyIsInvalid()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        (new ArrayCache($data))->getMultiple(['key1', 'key2', 1]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetMultipleValuesIfArgumentIsIncorrectType()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        (new ArrayCache($data))->getMultiple('123');
    }

    public function testSetsMultipleValuesAtOnce()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = new ArrayCache();

        $this->assertTrue($cache->setMultiple($data));
        $this->assertEquals($data, $cache->getMultiple(array_keys($data)));
    }


    public function testSetsMultipleValuesAtOnceFromTraversable()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $items = new ArrayObject($data);
        $cache = new ArrayCache();

        $this->assertTrue($cache->setMultiple($items));
        $this->assertEquals($data, $cache->getMultiple(array_keys($data)));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatesWhenAttemptingToSetMultipleValuesAtOnceFromTraversable()
    {
        $data  = [1 => 'value1', 'key2' => 'value2'];
        $items = new ArrayObject($data);
        (new ArrayCache())->setMultiple($items);
    }

    public function testDeletesDataBasedOnKey()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = new ArrayCache($data);

        $cache->delete('key1');

        $this->assertNull($cache->get('key1'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotDeleteIfKeyIsInvalid()
    {
        (new ArrayCache())->delete(1);
    }

    public function testDeletesMultipleItemsBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'];
        $cache = new ArrayCache($data);

        $cache->deleteMultiple(['key1', 'key2']);

        $this->assertNull($cache->get('key1'));
        $this->assertNull($cache->get('key2'));
        $this->assertEquals('value3', $cache->get('key3'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotDeleteMultipleValuesIfAnyKeyIsInvalid()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        (new ArrayCache($data))->deleteMultiple(['key1', 'key2', 1]);
    }

    public function testReturnsWhetherKeyExists()
    {
        $data  = ['key1' => 'value1', 'key2' => null, 'key3' => false];
        $cache = new ArrayCache($data);

        $this->assertTrue($cache->exists('key1'));
        // Expected to be null since we return null on failed get().
        $this->assertFalse($cache->exists('key2'));
        $this->assertTrue($cache->exists('key3'));
        $this->assertFalse($cache->exists('key4'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatesKeysWhenCheckingExistence()
    {
        (new ArrayCache())->exists(1);
    }

    public function testClearsAllData()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = new ArrayCache($data);

        $cache->clear();

        $this->assertNull($cache->get('key1'));
        $this->assertEquals(['key1' => null, 'key2' => null], $cache->getMultiple(array_keys($data)));
    }
}
