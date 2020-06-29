<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\Environment\TestEnvironment;
use DH\GUS\Exception\AuthStateException;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Handler\LoginHandler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class LoginHandlerTest extends TestCase
{
    public function testGetInputValues()
    {
        $instance = $this->getInstance();
        $reflectionObject = new ReflectionClass(LoginHandler::class);
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
        $input->ZalogujResult = true;
        $this->assertSame($instance, $instance->validateResponse($input));

        $input = new stdClass();
        $this->expectException(InvalidResponseException::class);
        $instance->validateResponse($input);
    }

    public function testParseResponse()
    {
        $instance = $this->getInstance();

        $input = new stdClass();
        $input->ZalogujResult = '123ABC';
        $this->assertSame('123ABC', $instance->parseResponse($input));

        $input = new stdClass();
        $input->ZalogujResult = '';
        $this->expectException(AuthStateException::class);
        $instance->parseResponse($input);
    }
    protected function getInstance()
    {
        $envStub = $this->createMock(TestEnvironment::class);

        $envStub->method('getLoginKey')
        ->willReturn('123ABC')
        ;

        return new LoginHandler($envStub);
    }
}
