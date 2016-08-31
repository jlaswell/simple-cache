<?php

namespace Realpage\SimpleCache\Tests;

use UnexpectedValueException;
use PHPUnit\Framework\TestCase;
use Realpage\SimpleCache\KeyValidation;

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
     * @expectedException UnexpectedValueException
     */
    public function testKeyContainsAtLeastOneCharacter()
    {
        $this->stub->validateKey('');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCannotGetUnlessKeyIsString()
    {
        $this->stub->validateKey(new \stdClass);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowLeftCurlyBrace()
    {
        $this->stub->validateKey('{key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowRightCurlyBrace()
    {
        $this->stub->validateKey('}key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowLeftParenthesis()
    {
        $this->stub->validateKey('(key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowRightParenthesis()
    {
        $this->stub->validateKey(')key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowForwardSlash()
    {
        $this->stub->validateKey('/key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowBackslash()
    {
        $this->stub->validateKey('\key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowAtSymbol()
    {
        $this->stub->validateKey('@key1');
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testDoesNotAllowColon()
    {
        $this->stub->validateKey(':key1');
    }
}
