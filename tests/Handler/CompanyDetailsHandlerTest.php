<?php

namespace DH\GUS\Tests\Handler\Tests;

use DH\GUS\CompanyIdType;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Handler\CompanyDetailsHandler;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class CompanyDetailsHandlerTest extends TestCase
{
    public function testValidateAndEstablishParamType_invalid_count_min()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($selfDouble, CompanyIdType::NIP, []);
    }

    public function testValidateAndEstablishParamType_invalid_count_max()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($selfDouble, CompanyIdType::NIP, $this->genRandomIds(21, 10));
    }

    public function testValidateAndEstablishParamType_nip()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        //single
        $returnVal = $method->invoke($selfDouble, CompanyIdType::NIP, $this->genRandomIds(1, 10));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::NIP ];
        $this->assertEquals($expected, $returnVal);

        //plural
        $returnVal = $method->invoke($selfDouble, CompanyIdType::NIP, $this->genRandomIds(20, 10));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::NIP ];
        $this->assertEquals($expected, $returnVal);

        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::NIP, $this->genRandomIds(1, 9));
    }

    public function testValidateAndEstablishParamType_nip_invalid_single()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($selfDouble, CompanyIdType::NIP, '111');
    }

    public function testValidateAndEstablishParamType_nip_invalid_multiple_onebroken()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $randomIds = $this->genRandomIds(5, 10);
        $randomIds[] = '111';
        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::NIP, $randomIds);
    }

    public function testValidateAndEstablishParamType_krs()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        //single
        $returnVal = $method->invoke($selfDouble, CompanyIdType::KRS, $this->genRandomIds(1, 10));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::KRS ];
        $this->assertEquals($expected, $returnVal);

        //plural
        $returnVal = $method->invoke($selfDouble, CompanyIdType::KRS, $this->genRandomIds(20, 10));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::KRS ];
        $this->assertEquals($expected, $returnVal);

        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::KRS, $this->genRandomIds(1, 9));
    }

    public function testValidateAndEstablishParamType_krs_invalid_single()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($selfDouble, CompanyIdType::KRS, '111');
    }

    public function testValidateAndEstablishParamType_krs_invalid_multiple_onebroken()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $randomIds = $this->genRandomIds(5, 10);
        $randomIds[] = '111';
        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::KRS, $randomIds);
    }

    public function testValidateAndEstablishParamType_regon()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        //single
        $returnVal = $method->invoke($selfDouble, CompanyIdType::REGON, $this->genRandomIds(1, 9));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::REGON ];
        $this->assertEquals($expected, $returnVal);

        //plural 9
        $returnVal = $method->invoke($selfDouble, CompanyIdType::REGON, $this->genRandomIds(20, 9));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::REGON . '9' ];
        $this->assertEquals($expected, $returnVal);

        //plural 14
        $returnVal = $method->invoke($selfDouble, CompanyIdType::REGON, $this->genRandomIds(20, 14));
        $expected = CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::REGON . '14' ];
        $this->assertEquals($expected, $returnVal);

        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::REGON, $this->genRandomIds(1, 5));
    }

    public function testValidateAndEstablishParamType_regon_invalid_single()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $this->expectException(InvalidArgumentException::class);
        $method->invoke($selfDouble, CompanyIdType::REGON, '111');
    }

    public function testValidateAndEstablishParamType_regon_invalid_multiple_onebroken()
    {
        list($selfDouble, $method) = $this->getInstanceForValidationTests();

        $randomIds = $this->genRandomIds(5, 9);
        $randomIds[] = '12345678901234';
        $this->expectException(InvalidArgumentException::class);
        $returnVal = $method->invoke($selfDouble, CompanyIdType::REGON, $randomIds);
    }

    /**
     * @dataProvider constructProvider
     */
    public function testGetInputValues($inputType, $inputValue, $expectedInputType)
    {
        $instance = new CompanyDetailsHandler($inputType, $inputValue);
        $reflectionObject = new ReflectionClass(CompanyDetailsHandler::class);
        $inputKey = $reflectionObject->getConstant('INPUT_KEY');

        $inputValues = $instance->getInputValues();
        $values = $inputValues[$inputKey];

        $expectedValue = is_array($inputValue) ? join(',', $inputValue) : $inputValue;
        ;
        $this->assertEquals($expectedInputType, key($values));
        $this->assertEquals($expectedValue, $values[$expectedInputType]);
    }

    public function constructProvider()
    {
        return [
            [ CompanyIdType::KRS, '1234567890', CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::KRS] ],
            [ CompanyIdType::KRS, ['1234567890','1234567891'], CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::KRS] ],
            [CompanyIdType::NIP, '1234567890', CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::NIP] ],
            [CompanyIdType::NIP, ['1234567890','1234567891'], CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::NIP] ],
            [CompanyIdType::REGON, '123456789', CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::REGON] ],
            [CompanyIdType::REGON, ['123456789','123456789'], CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::REGON . '9'] ],
            [CompanyIdType::REGON, '12345678901234', CompanyDetailsHandler::ID_TYPE_MAPPING_SINGLE[ CompanyIdType::REGON] ],
            [CompanyIdType::REGON, ['12345678901234','12345678901234'], CompanyDetailsHandler::ID_TYPE_MAPPING_PLURAL[ CompanyIdType::REGON . '14'] ]
        ];
    }

    public function testValidateResponse()
    {
        $instance = new CompanyDetailsHandler(CompanyIdType::NIP, '1234567890');

        $input = new stdClass();
        $input->DaneSzukajPodmiotyResult = new stdClass;
        $this->assertSame($instance, $instance->validateResponse($input));

        $input = new stdClass();
        $this->expectException(InvalidResponseException::class);
        $instance->validateResponse($input);
    }

    public function testParseResponse()
    {
        $instance = new CompanyDetailsHandler(CompanyIdType::NIP, '1112223344');

        $input = new stdClass();
        $input->DaneSzukajPodmiotyResult = '<data>
        <dane>
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
        </dane>
    </data>';
        $companyDetails = $instance->parseResponse($input);
        $this->assertArrayHasKey('1112223344', $companyDetails);
        $this->assertEquals('222140111', $companyDetails['1112223344']->getRegon());

        $input = new stdClass();
        $input->DaneSzukajPodmiotyResult = '<data>
        <dane>
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
        </dane>
        <dane>
        <Nip>1112223345</Nip>
        <Regon>222140112</Regon>
        <Nazwa>SUPERFIRMA2 Nowak Andrzej</Nazwa>
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
        </dane>
    </data>';
        $companyDetails = $instance->parseResponse($input);
        $this->assertArrayHasKey('1112223344', $companyDetails);
        $this->assertArrayHasKey('1112223345', $companyDetails);
        $this->assertEquals('222140111', $companyDetails['1112223344']->getRegon());
        $this->assertEquals('222140112', $companyDetails['1112223345']->getRegon());

        $input->DaneSzukajPodmiotyResult = '<data>,.dsadsa<';
        $this->expectException(InvalidResponseException::class);
        $instance->parseResponse($input);
    }
    protected function genRandomIds($count, $length)
    {
        $values = [];
        $min = pow(10, $length - 1);
        $max = $min * 10 - 1;
        for ($i = 0; $i< $count; $i++) {
            $values[] = mt_rand($min, $max);
        }

        if (!isset($values[1])) {
            return $values[0];
        }

        return $values;
    }

    protected function getInstanceForValidationTests()
    {
        $reflectionObject = new ReflectionClass(CompanyDetailsHandler::class);
        $method = $reflectionObject->getMethod('validateAndEstablishParamType');
        $method->setAccessible(true);

        $selfDouble = $this->getMockBuilder(CompanyDetailsHandler::class)
                            ->disableOriginalConstructor()
                            ->getMock()
                            ;

        return [ $selfDouble, $method ];
    }
}
