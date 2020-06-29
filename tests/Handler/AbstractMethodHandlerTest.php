<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\Handler\AbstractMethodHandler;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class AbstractMethodHandlerTest extends TestCase
{
    public function testIsSessionRequired()
    {
        $instance = $this->getInstance();
        $reflectionObject = new ReflectionClass(AbstractMethodHandler::class);
        $constantIsSessionRequired = $reflectionObject->getConstant('SESSION_REQUIRED');
        $this->assertEquals($constantIsSessionRequired, $instance->isSessionRequired());
    }

    public function testGetSoapMethodName()
    {
        $instance = $this->getInstance();

        $this->assertEquals(AbstractMethodHandler::NAME, $instance->getSoapMethodName());
    }
    protected function getInstance()
    {
        return new class extends AbstractMethodHandler {
            public function getInputValues()
            {
            }
            public function validateResponse($response)
            {
            }
            public function parseResponse($response)
            {
            }
        };
    }
}
