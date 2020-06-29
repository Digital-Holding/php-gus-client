<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Handler\LogoutHandler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class LogoutHandlerTest extends TestCase
{
    public function testGetInputValues()
    {
        $instance = $this->getInstance();
        $reflectionObject = new ReflectionClass(LogoutHandler::class);
        $inputKey = $reflectionObject->getConstant('INPUT_KEY');

        $expected = [
            $inputKey => '123ABC'
        ];

        $this->assertEquals($expected, $instance->getInputValues());
    }

    public function testValidateResponse()
    {
        $instance = $this->getInstance();

        $input = new stdClass();
        $input->WylogujResult = true;
        $this->assertSame($instance, $instance->validateResponse($input));

        $input = new stdClass();
        $this->expectException(InvalidResponseException::class);
        $instance->validateResponse($input);
    }

    public function testParseResponse()
    {
        $instance = $this->getInstance();

        $input = new stdClass();
        $input->WylogujResult = true;
        $this->assertTrue($instance->parseResponse($input));

        $input = new stdClass();
        $input->WylogujResult = false;
        $this->assertFalse($instance->parseResponse($input));
    }
    protected function getInstance()
    {
        return new LogoutHandler('123ABC');
    }
}
