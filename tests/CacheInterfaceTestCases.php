<?php

namespace Jlaswell\SimpleCache\Tests;

use ArrayObject;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

trait CacheInterfaceTestCases
{
    public function testImplementsCacheInterface()
    {
        $this->assertInstanceOf(CacheInterface::class, $this->buildCache());
    }

    public function testCacheCanBeSetOnInstantiation()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $this->assertEquals($data['key1'], $cache->get('key1'));
        $this->assertEquals($data['key2'], $cache->get('key2'));
    }

    public function testCacheCanGetWithADefaultValue()
    {
        $data  = ['key1' => 'value1'];
        $cache = $this->buildCache();

        $this->assertEquals(null, $cache->get('key2'));
        $this->assertEquals('value2', $cache->get('key2', 'value2'));
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotGetUnlessKeyIsString()
    {
        $this->buildCache()->get(1);
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotGetKeyWithInvalidCharacter()
    {
        $this->buildCache()->get(':');
    }

    public function testCacheCanSetASimpleValue()
    {
        $cache = $this->buildCache();

        $this->assertNull($cache->get('key1'));
        $this->assertTrue($cache->set('key2', null));
        $this->assertTrue($cache->set('key1', 'value1'));
        $this->assertEquals('value1', $cache->get('key1'));
    }

    public function testCacheErrorsCorrectlyWhenSetFails()
    {
        $this->markTestIncomplete(
            'Need a means of erroring out drivers during this test.'
        );
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCacheCannotSetValueUsingInvalidKey()
    {
        $this->buildCache()->set(1, 'value1');
    }

    public function testCacheCanSetMoreComplexValues()
    {
        $this->markTestIncomplete(
            'This test will need to focus on non-string values.'
        );
    }

    public function testCacheCanSetListOfNaughtyStringValues()
    {
        $this->markTestIncomplete(
            'This test will need to focus on the big list of naughty strings.'
        );
    }

    public function testDeletesDataBasedOnKey()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $this->assertTrue($cache->delete('key1'));
        $this->assertNull($cache->get('key1'));
        // @todo note in docs that we return true if key is non-existant but
        // delete was successfully called.
        $this->assertTrue($cache->delete('key1'));
    }

    public function testFailsToDeleteDataOnError()
    {
        $this->markTestIncomplete(
            'Need a means of erroring out drivers during this test.'
        );
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotDeleteIfKeyIsInvalid()
    {
        $this->buildCache()->delete(1);
    }

    public function testClearsAllData()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);

        $cache->clear();

        $this->assertNull($cache->get('key1'));
        $this->assertEquals(['key1' => null, 'key2' => null], $cache->getMultiple(array_keys($data)));
    }

    public function testFailsToClearsDataOnError()
    {
        $this->markTestIncomplete(
            'Need a means of erroring out drivers during this test.'
        );
    }

    public function testGetsMultipleValuesBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $keys  = new ArrayObject(['key1' => '', 'key2' => '']);
        $cache = $this->buildCache($data);

        $this->assertEquals($data, $cache->getMultiple(['key1', 'key2']));
        $this->assertEquals($data, $cache->getMultiple($keys));
    }

    public function testProvidesDefaultForMissingKeysWhenGettingMultipleValues()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2'];
        $cache = $this->buildCache($data);
        $expectedData = array_merge($data, ['key3' => null]);
        $expectedNewDefault = array_merge($data, ['key3' => 'foo']);

        $this->assertEquals($expectedData, $cache->getMultiple(array_keys($expectedData)));
        $this->assertEquals($expectedNewDefault, $cache->getMultiple(array_keys($expectedNewDefault), 'foo'));
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotGetMultipleValuesIfAnyKeyIsInvalid()
    {
        $data = ['key1' => 'value1', 'key2' => 'value2'];
        $this->buildCache($data)->getMultiple(['key1', 'key2', 1]);
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotGetMultipleValuesForNonTraversable()
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
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testCannotSetMultipleValuesForNonTraversable()
    {
        $data  = [1 => 'value1', 'key2' => 'value2'];
        $items = new ArrayObject($data);
        $this->buildCache()->setMultiple($items);
    }

    public function testDeletesMultipleItemsBasedOnKeys()
    {
        $data  = ['key1' => 'value1', 'key2' => 'value2', 'key3' => 'value3'];
        $cache = $this->buildCache($data);


        $this->assertTrue($cache->deleteMultiple(['key1', 'key2']));
        $this->assertNull($cache->get('key1'));
        $this->assertNull($cache->get('key2'));
        $this->assertEquals('value3', $cache->get('key3'));
    }

    public function testFailsToDeleteMultpleDataOnError()
    {
        $this->markTestIncomplete(
            'Need a means of erroring out drivers during this test.'
        );
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
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

        // $this->assertTrue($cache->has('key1'));
        // Expected to be false since we return null on failed get().
        // $this->assertFalse($cache->has('key2'));
        // $this->assertTrue($cache->has('key3'));
        $this->assertFalse($cache->has('key4'));
    }

    /**
     * @expectedException Psr\SimpleCache\InvalidArgumentException
     */
    public function testValidatesKeysWhenCheckingExistence()
    {
        $this->buildCache()->has(1);
    }
}
