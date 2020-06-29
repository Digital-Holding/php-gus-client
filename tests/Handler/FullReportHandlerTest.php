<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Handler\FullReportHandler;
use DH\GUS\Model\CompanyDetails;
use DH\GUS\Model\ReportType\AbstractReportType;
use DH\GUS\Tests\Handler\ReportTypeMock;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use SimpleXMLElement;
use stdClass;

class FullReportHandlerTest extends TestCase
{
    protected function setUp():void
    {
        $companyData = '        <dane>
        <Nip>1112223344</Nip>
        <Regon>222140111</Regon>
        <Nazwa>SUPERFIRMA Nowak Andrzej</Nazwa>
        <Wojewodztwo>Wielkopolskie</Wojewodztwo>
        <Powiat>Pilski</Powiat>
        <Gmina>Ujście</Gmina>
        <Miejscowosc>Jabłonowo</Miejscowosc>
        <KodPocztowy>64-999</KodPocztowy>
        <Ulica>Marynarska</Ulica>
        <NrNieruchomosci>11A</NrNieruchomosci>
        <NrLokalu>123</NrLokalu>
        <Typ>F</Typ>
        <SilosID>1</SilosID>
        <DataZakonczeniaDzialalnosci/>
        <MiejscowoscPoczty>Duże miasto</MiejscowoscPoczty>
        </dane>';
        $companyDetails =new CompanyDetails(new SimpleXMLElement($companyData));
    }

    public function testGetInputValues()
    {
        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                        ->willReturn('123456789')
                        ;

        $companyDetails->method('getSiloId')
                        ->willReturn('2')
                        ;

        $reportTypeObject= $this->createMock(AbstractReportType::class);

        $reportTypeObject->method('getRegonLen')
                        ->willReturn([9])
                        ;

        $reportTypeObject->method('getSupportedSiloIds')
                        ->willReturn([1, 2, 3])
                        ;
        class_alias(ReportTypeMock::class, '\\DH\\GUS\\Model\\ReportType\\ReportMockReportType', true);
        $instance = new FullReportHandler('ReportMock', $companyDetails);

        $reflectionObject = new ReflectionClass(FullReportHandler::class);

        $inputValues = $instance->getInputValues();
        $expected = [
            $reflectionObject->getConstant('ENTITY_ID') => '123456789',
            $reflectionObject->getConstant('INPUT_KEY') => 'ReportMock',
        ];

        $this->assertEquals($expected, $inputValues);
    }

    public function testInvalidRegon()
    {
        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                        ->willReturn('12345678901234')
                        ;

        $companyDetails->method('getSiloId')
                        ->willReturn('2')
                        ;

        class_alias(ReportTypeMock::class, '\\DH\\GUS\\Model\\ReportType\\ReportMockReportType', true);
        $this->expectException(InvalidArgumentException::class);
        $instance = new FullReportHandler('ReportMock', $companyDetails);
    }

    public function testInvalidSiloId()
    {
        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                        ->willReturn('123456789')
                        ;

        $companyDetails->method('getSiloId')
                        ->willReturn('1')
                        ;

        class_alias(ReportTypeMock::class, '\\DH\\GUS\\Model\\ReportType\\ReportMockReportType', true);
        $this->expectException(InvalidArgumentException::class);
        $instance = new FullReportHandler('ReportMock', $companyDetails);
    }

    public function testValidateResponse()
    {
        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                        ->willReturn('123456789')
                        ;

        $companyDetails->method('getSiloId')
                        ->willReturn('2')
                        ;

        class_alias(ReportTypeMock::class, '\\DH\\GUS\\Model\\ReportType\\ReportMockReportType', true);
        $instance = new FullReportHandler('ReportMock', $companyDetails);

        $input = new stdClass();
        $input->DanePobierzPelnyRaportResult = true;
        $this->assertSame($instance, $instance->validateResponse($input));

        $input = new stdClass();
        $this->expectException(InvalidResponseException::class);
        $instance->validateResponse($input);
    }

    public function testParseResponse()
    {
        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                        ->willReturn('123456789')
                        ;

        $companyDetails->method('getSiloId')
                        ->willReturn('2')
                        ;

        class_alias(ReportTypeMock::class, '\\DH\\GUS\\Model\\ReportType\\ReportMockReportType', true);
        $instance = new FullReportHandler('ReportMock', $companyDetails);

        $input = new stdClass();
        $input->DanePobierzPelnyRaportResult = '<root><dane>
            <SampleReportData>1112223344</SampleReportData>
            </dane></root>';
        $reportParsed = $instance->parseResponse($input);
        $this->assertArrayHasKey('SampleReportData', $reportParsed);

        $input = new stdClass();
        $input->DanePobierzPelnyRaportResult = '<root><dane/></root>';
        $reportParsed = $instance->parseResponse($input);
        $this->assertEmpty($reportParsed);

        $input->DanePobierzPelnyRaportResult = '<data>,.dsadsa<';
        $this->expectException(InvalidResponseException::class);
        $instance->parseResponse($input);
    }
}
