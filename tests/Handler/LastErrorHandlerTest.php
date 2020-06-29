<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Handler\LastErrorHandler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class LastErrorHandlerTest extends TestCase
{
    public function testGetInputValues()
    {
        $instance = $this->getInstance();
        $reflectionObject = new ReflectionClass(LastErrorHandler::class);
        $inputKey = $reflectionObject->getConstant('INPUT_KEY');
        $inputValue = $reflectionObject->getConstant('INPUT_VALUE');

        $expected = [
            $inputKey => $inputValue
        ];

        $this->assertEquals($expected, $instance->getInputValues());
    }

    public function testValidateResponse()
    {
        $instance = $this->getInstance();

        $input = new stdClass();
        $input->GetValueResult = 'abc';
        $this->assertSame($instance, $instance->validateResponse($input));

        $input = new stdClass();
        $this->expectException(InvalidResponseException::class);
        $instance->validateResponse($input);
    }

    public function testParseResponse()
    {
        $instance = $this->getInstance();

        $input = new stdClass();
        $input->GetValueResult = 'sample error';
        $this->assertEquals('sample error', $instance->parseResponse($input));
    }
    protected function getInstance()
    {
        return new LastErrorHandler();
    }
}
