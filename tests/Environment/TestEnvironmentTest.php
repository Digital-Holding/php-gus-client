<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Environment\Tests;

use DH\GUS\Environment\TestEnvironment;
use PHPUnit\Framework\TestCase;

final class TestEnvironmentTest extends TestCase
{
    protected $environment;

    protected function setUp(): void
    {
        $this->environment = new TestEnvironment();
    }

    public function testGetLoginKey()
    {
        /** @var TestEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getLoginKey(), TestEnvironment::TEST_LOGIN_KEY);
    }

    public function testGetWsdl()
    {
        /** @var TestEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getWsdl(), TestEnvironment::WSDL);
    }

    public function testGetEndpointUri()
    {
        /** @var TestEnvironment */
        $env = $this->environment;
        $this->assertEquals($env->getEndpointUri(), TestEnvironment::ENDPOINT);
    }

    public function testGetIgnoreSsl()
    {
        /** @var TestEnvironment */
        $env = $this->environment;
        $this->assertTrue($env->getIgnoreSsl());
    }
}
