<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Environment\Tests;

use DH\GUS\Environment\ProductionEnvironment;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ProductionEnvironmentTest extends TestCase
{
    protected $environment;

    protected function setUp(): void
    {
        $this->environment = new ProductionEnvironment('my login key');
    }

    public function testGetLoginKey()
    {
        /** @var ProductionEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getLoginKey(), 'my login key');
    }

    public function testGetWsdl()
    {
        /** @var ProductionEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getWsdl(), ProductionEnvironment::WSDL);
    }

    public function testGetEndpointUri()
    {
        /** @var ProductionEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getEndpointUri(), ProductionEnvironment::ENDPOINT);
    }

    public function testGetIgnoreSsl()
    {
        /** @var ProductionEnvironment */
        $env = $this->environment;
        $this->assertFalse($env->getIgnoreSsl());
    }

    public function testEmptyLoginKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $env = new ProductionEnvironment('');
    }
}
