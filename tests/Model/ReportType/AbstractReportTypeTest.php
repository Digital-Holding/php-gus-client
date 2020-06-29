<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Model\ReportType\Tests;

use DH\GUS\Model\ReportType\AbstractReportType;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class AbstractReportTypeTest extends TestCase
{
    protected $consts;

    /** @var AbstractReportType */
    protected $instance;

    protected function setUp(): void
    {
        $oClass = new ReflectionClass(AbstractReportType::class);
        $this->consts = $oClass->getConstants();

        $this->instance = new class extends AbstractReportType {
        };
    }

    public function testGetName()
    {
        $this->assertEquals($this->consts['NAME'], $this->instance->getName());
    }

    public function testGetSupportedSiloIds()
    {
        $this->assertEquals($this->consts['SUPPORTED_SILO_IDS'], $this->instance->getSupportedSiloIds());
    }

    public function testGetSupportedType()
    {
        $this->assertEquals($this->consts['SUPPORTED_TYPE'], $this->instance->getSupportedType());
    }

    public function testGetGusDescription()
    {
        $this->assertEquals($this->consts['GUS_DESCRIPTION'], $this->instance->getGusDescription());
    }

    public function testGetRegonLen()
    {
        $this->assertEquals($this->consts['REGON_LEN'], $this->instance->getRegonLen());
    }
}
