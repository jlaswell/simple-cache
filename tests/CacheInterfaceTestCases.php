<?php

namespace Realpage\SimpleCache\Tests;

use ArrayObject;
use InvalidArgumentException;
use UnexpectedValueException;

trait CacheInterfaceTestCases
{
    public function testCacheCanBeSetOnInstantiation()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $this->assertEquals($data['key1'], $cache->get('key1'));
        $this->assertEquals($data['key2'], $cache->get('key2'));
    }

    public function testCacheCanSetASimpleValue()
    {
        $cache = $this->buildCache();

        $this->assertNull($cache->get('key1'));
        $this->assertTrue($cache->set('key1', 'value1'));
        $this->assertEquals('value1', $cache->get('key1'));
        $this->assertFalse($cache->set('key2', null));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetUnlessKeyIsString()
    {
        $this->buildCache()->get(1);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testCannotGetKeyWithInvalidCharacter()
    {
        $this->buildCache()->get(':');
    }

    public function testGetsMultipleValuesBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $keys  = new ArrayObject(['key1' => '', 'key2' => '']);
        $cache = $this->buildCache($data);

        $this->assertEquals($data, $cache->getMultiple(['key1', 'key2']));
        $this->assertEquals($data, $cache->getMultiple($keys));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetMultipleValuesIfAnyKeyIsInvalid()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $this->buildCache($data)->getMultiple(['key1', 'key2', 1]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetMultipleValuesIfArgumentIsIncorrectType()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $this->buildCache($data)->getMultiple('123');
    }

    public function testSetsMultipleValuesAtOnce()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache();

        $this->assertTrue($cache->setMultiple($data));
        $this->assertEquals($data, $cache->getMultiple(array_keys($data)));
    }


    public function testSetsMultipleValuesAtOnceFromTraversable()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $items = new ArrayObject($data);
        $cache = $this->buildCache();

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
        $this->buildCache()->setMultiple($items);
    }

    public function testDeletesDataBasedOnKey()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $this->assertEquals('value1', $cache->get('key1'));
        $cache->delete('key1');
        $this->assertNull($cache->get('key1'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotDeleteIfKeyIsInvalid()
    {
        $this->buildCache()->delete(1);
    }

    public function testDeletesMultipleItemsBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'];
        $cache = $this->buildCache($data);

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
        $this->buildCache($data)->deleteMultiple(['key1', 'key2', 1]);
    }

    public function testReturnsWhetherKeyExists()
    {
        $data  = ['key1' => 'value1', 'key2' => null, 'key3' => false];
        $cache = $this->buildCache($data);

        $this->assertTrue($cache->exists('key1'));
        // Expected to be false since we return null on failed get().
        $this->assertFalse($cache->exists('key2'));
        $this->assertTrue($cache->exists('key3'));
        $this->assertFalse($cache->exists('key4'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidatesKeysWhenCheckingExistence()
    {
        $this->buildCache()->exists(1);
    }

    public function testClearsAllData()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $cache->clear();

        $this->assertNull($cache->get('key1'));
        $this->assertEquals(['key1' => null, 'key2' => null], $cache->getMultiple(array_keys($data)));
    }
}
