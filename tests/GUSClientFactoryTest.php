<?php

declare(strict_types=1);

namespace DH\GUS\Tests;

use ArgumentCountError;
use DH\GUS\Environment\ProductionEnvironment;
use DH\GUS\Environment\TestEnvironment;
use DH\GUS\GUSClientFactory;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class GUSClientFactoryTest extends TestCase
{
    public function testTestEnvironment()
    {
        $client = GUSClientFactory::createWithEnvironment('test', 'irrelevant');
        $environment = $client->getEnvironment();
        $this->assertTrue($environment instanceof TestEnvironment);
        $this->assertEquals($environment->getEndpointUri(), TestEnvironment::ENDPOINT);
        $this->assertEquals($environment->getLoginKey(), TestEnvironment::TEST_LOGIN_KEY);
    }

    public function testTestEnvironmentWithoutLoginKey()
    {
        $client = GUSClientFactory::createWithEnvironment('test');
        $environment = $client->getEnvironment();
        $this->assertTrue($environment instanceof TestEnvironment);
        $this->assertEquals($environment->getEndpointUri(), TestEnvironment::ENDPOINT);
        $this->assertEquals($environment->getLoginKey(), TestEnvironment::TEST_LOGIN_KEY);
    }

    public function testProductionEnvironment()
    {
        $client = GUSClientFactory::createWithEnvironment('production', 'my login key');
        $environment = $client->getEnvironment();
        $this->assertTrue($environment instanceof ProductionEnvironment);
        $this->assertEquals($environment->getEndpointUri(), ProductionEnvironment::ENDPOINT);
        $this->assertEquals($environment->getLoginKey(), 'my login key');
    }

    public function testProductionEnvironmentWithoutLoginKey()
    {
        $this->expectException(ArgumentCountError::class);
        $client = GUSClientFactory::createWithEnvironment('production');
    }

    public function testForbiddenConstructor()
    {
        $reflection = new ReflectionMethod(GUSClientFactory::class, '__construct');
        $this->assertFalse($reflection->isPublic());
    }
}
