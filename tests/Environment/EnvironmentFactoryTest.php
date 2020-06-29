<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Environment\Tests;

use DH\GUS\Environment\EnvironmentFactory;
use DH\GUS\Environment\ProductionEnvironment;
use DH\GUS\Environment\TestEnvironment;
use DH\GUS\Exception\EnvironmentNotFoundException;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class EnvironmentFactoryTest extends TestCase
{
    public function testTestEnvironment()
    {
        $environment = EnvironmentFactory::createEnvironment('test', 'irrelevant');
        $this->assertTrue($environment instanceof TestEnvironment);
        $this->assertEquals($environment->getEndpointUri(), TestEnvironment::ENDPOINT);
        $this->assertEquals($environment->getLoginKey(), TestEnvironment::TEST_LOGIN_KEY);
    }

    public function testProductionEnvironment()
    {
        $environment = EnvironmentFactory::createEnvironment('production', 'my login key');
        $this->assertTrue($environment instanceof ProductionEnvironment);
        $this->assertEquals($environment->getEndpointUri(), ProductionEnvironment::ENDPOINT);
        $this->assertEquals($environment->getLoginKey(), 'my login key');
    }

    public function testUnknownEnvironment()
    {
        $this->expectException(EnvironmentNotFoundException::class);
        $environment = EnvironmentFactory::createEnvironment('dsahdksaeaskleklsarbn kla', 'my login key'); //TODO randomize, check file exists
    }

    public function testForbiddenConstructor()
    {
        $reflection = new ReflectionMethod(EnvironmentFactory::class, '__construct');
        $this->assertFalse($reflection->isPublic());
    }
}
