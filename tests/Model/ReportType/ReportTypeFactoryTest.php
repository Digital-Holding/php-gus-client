<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Model\ReportType\Tests;

use DH\GUS\Exception\ReportTypeNotFoundException;
use DH\GUS\Model\ReportType\AbstractReportType;
use DH\GUS\Model\ReportType\ReportTypeFactory;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class ReportTypeFactoryTest extends TestCase
{
    public function testForbiddenConstructor()
    {
        $reflection = new ReflectionMethod(ReportTypeFactory::class, '__construct');
        $this->assertFalse($reflection->isPublic());
    }

    public function testUnknownReport()
    {
        $this->expectException(ReportTypeNotFoundException::class);
        $client = ReportTypeFactory::createReportType('dsadsadasdsadsadasdsa');
    }

    public function testKnownReport()
    {
        $report = ReportTypeFactory::createReportType('BIR11JednLokalnaOsFizycznejPkd');
        $this->assertTrue($report instanceof AbstractReportType);
    }
}
