<?php

declare(strict_types=1);

namespace DH\GUS\Tests;

use DH\GUS\CompanyIdType;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class CompanyIdTest extends TestCase
{
    public function testStaticVars()
    {
        $class = new ReflectionClass(CompanyIdType::class);
        $vars = $class->getConstants();

        $this->assertArrayHasKey('NIP', $vars);
        $this->assertArrayHasKey('REGON', $vars);
        $this->assertArrayHasKey('KRS', $vars);
    }
}
