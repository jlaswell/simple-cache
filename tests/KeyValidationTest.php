<?php

namespace Jlaswell\SimpleCache\Tests;

use PHPUnit\Framework\TestCase;
use Jlaswell\SimpleCache\InvalidKeyException;
use Jlaswell\SimpleCache\KeyValidation;

class KeyValidationTest extends TestCase
{
    private $stub;

    public function setUp()
    {
        $this->stub = $this->getObjectForTrait(KeyValidation::class);
    }

    public function testCanReturnAPassingKey()
    {
        $this->assertEquals('key1', $this->stub->validateKey('key1'));
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testKeyContainsAtLeastOneCharacter()
    {
        $this->stub->validateKey('');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testCannotGetUnlessKeyIsString()
    {
        $this->stub->validateKey(new \stdClass);
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowLeftCurlyBrace()
    {
        $this->stub->validateKey('{key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowRightCurlyBrace()
    {
        $this->stub->validateKey('}key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowLeftParenthesis()
    {
        $this->stub->validateKey('(key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowRightParenthesis()
    {
        $this->stub->validateKey(')key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowForwardSlash()
    {
        $this->stub->validateKey('/key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowBackslash()
    {
        $this->stub->validateKey('\key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowAtSymbol()
    {
        $this->stub->validateKey('@key1');
    }

    /**
     * @expectedException \Jlaswell\SimpleCache\InvalidKeyException
     */
    public function testDoesNotAllowColon()
    {
        $this->stub->validateKey(':key1');
    }
}
