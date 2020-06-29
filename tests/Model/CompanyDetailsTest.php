<?php

declare(strict_types=1);

namespace DH\GUS\Tests\Model\Tests;

use DateTime;
use DateTimeInterface;
use DH\GUS\GUSClient;
use DH\GUS\Model\CompanyDetails;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

final class CompanyDetailsTest extends TestCase
{
    protected $companyDetailsFull;
    protected $companyDetailsClosedMissingData;

    protected function setUp(): void
    {
        $xmlElement = new SimpleXMLElement('
        <data>
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
        </data>
        ');

        $this->companyDetailsFull = new CompanyDetails($xmlElement);

        $xmlElement = new SimpleXMLElement('
        <data>
            <Nip>1112223344</Nip>
            <Regon>222140111</Regon>
            <StatusNip>5</StatusNip>
            <Nazwa>SUPERFIRMA Nowak Andrzej</Nazwa>
            <Wojewodztwo>Wielkopolskie</Wojewodztwo>
            <Powiat>Pilski</Powiat>
            <Gmina>Ujście</Gmina>
            <Miejscowosc>Jabłonowo</Miejscowosc>
            <KodPocztowy>64-999</KodPocztowy>
            <Ulica>Marynarska</Ulica>
            <NrNieruchomosci>11A</NrNieruchomosci>
            <NrLokalu></NrLokalu>
            <Typ>F</Typ>
            <SilosID>1</SilosID>
            <DataZakonczeniaDzialalnosci>2015-02-03</DataZakonczeniaDzialalnosci>
        </data>
        ');

        $this->companyDetailsClosedMissingData = new CompanyDetails($xmlElement);
    }

    public function testGetNip()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getNip(), '1112223344');
    }

    public function testGetRegon()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getRegon(), '222140111');
    }

    public function testGetNipStatus_null()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getNipStatus(), null);
    }

    public function testGetNipStatus()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsClosedMissingData;
        $this->assertEquals($companyDetails->getNipStatus(), '5');
    }

    public function testGetName()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getName(), 'SUPERFIRMA Nowak Andrzej');
    }

    public function testVoivodeship()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getVoivodeship(), 'Wielkopolskie');
    }

    public function testGetDistrict()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getDistrict(), 'Pilski');
    }

    public function testGetMunicipality()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getMunicipality(), 'Ujście');
    }

    public function testGetCity()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getCity(), 'Jabłonowo');
    }

    public function testGetPostCode()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getPostCode(), '64-999');
    }

    public function testGetStreet()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getStreet(), 'Marynarska');
    }

    public function testGetHouseNumber()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getHouseNumber(), '11A');
    }

    public function testGetFlatNumber()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getFlatNumber(), '123');
    }

    public function testGetFlatNumber_expected_missing()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsClosedMissingData;
        $this->assertEquals($companyDetails->getFlatNumber(), null);
    }

    public function testGetType()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getType(), 'F');
        $this->assertArrayHasKey($companyDetails->getType(), $companyDetails::COMPANY_TYPE);
    }

    public function testGetTypeTranslated()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails::COMPANY_TYPE[ $companyDetails->getType() ], $companyDetails->getTypeTranslated());
    }

    public function testGetSiloId()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getSiloId(), '1');
        $this->assertArrayHasKey($companyDetails->getSiloId(), $companyDetails::SILO_ID_TYPE);
    }

    public function testGetSiloIdTranslated()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails::SILO_ID_TYPE[ $companyDetails->getSiloId() ], $companyDetails->getSiloIdTranslated());
    }

    public function testGetPostCity()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getPostCity(), 'Duże miasto');
    }

    public function testGetPostCity_expected_missing()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsClosedMissingData;
        $this->assertEquals($companyDetails->getPostCity(), null);
    }

    public function testGetClosedAt()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsFull;
        $this->assertEquals($companyDetails->getClosedAt(), null);
    }

    public function testGetClosedAt_got_value()
    {
        /** @var CompanyDetails */
        $companyDetails = $this->companyDetailsClosedMissingData;
        $this->assertTrue($companyDetails->getClosedAt() instanceof DateTimeInterface);
        $tz = GUSClient::getGusTimeZone();
        $expected = new DateTime('2015-02-03', $tz);
        $this->assertEquals($companyDetails->getClosedAt()->getTimestamp(), $expected->getTimestamp());
    }
}
