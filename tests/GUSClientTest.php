<?php

namespace DH\GUS;

use DH\GUS\Environment\TestEnvironment;
use DH\GUS\Exception\AuthStateException;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Exception\SoapCallException;
use DH\GUS\Handler\AbstractMethodHandler;
use DH\GUS\Model\CompanyDetails;
use Exception;
use IDCT\Networking\Soap\Client;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class GUSClientTest extends TestCase
{
    protected $headersBuilder;
    protected $environment;
    protected $soapClient;
    protected $sessionId;

    protected static $gusTimeZone;

    public function testConstructorWithEnvironment()
    {
        $environment = new TestEnvironment();
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->setMethods(['createSoapClient'])
                           ->disableOriginalConstructor()
                           ->getMock()
                           ;

        $soapClient = new Client(null, ['location' => $environment->getEndpointUri(), 'uri' => 'irrelevant']);
        $selfDouble->method('createSoapClient')
                   ->willReturn($soapClient)
                   ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $constructor = $reflectedClass->getConstructor();
        $constructor->invoke($selfDouble, $environment);

        $this->assertSame($selfDouble->getEnvironment(), $environment);
        $method = $reflectedClass->getMethod('getSoapClient');
        $method->setAccessible(true);
        $this->assertSame($method->invoke($selfDouble), $soapClient);
    }

    public function testGetSoapOptions()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->getMock()
        ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $soapOptions = $reflectedClass->getConstant('SOAP_OPTIONS');

        $method = $reflectedClass->getMethod('getSoapOptions');
        $method->setAccessible(true);
        $this->assertSame($soapOptions, $method->invoke($selfDouble));
    }

    public function testCreateSoapClient()
    {
        $env = $this->getMockBuilder(TestEnvironment::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getWsdl', 'getIgnoreSsl'])
                           ->getMock()
        ;

        $env->method('getWsdl')
            ->willReturn(null)
            ;

        $env->method('getIgnoreSsl')
            ->willReturn(true)
            ;

        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getSoapOptions'])
                           ->getMock()
        ;

        $selfDouble->method('getSoapOptions')
                   ->willReturn(['uri' => 'irrelevant', 'location' => $env->getEndpointUri() ])
                   ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('createSoapClient');
        $method->setAccessible(true);
        $createdClient = $method->invoke($selfDouble, $env);
        $this->assertTrue($createdClient->getIgnoreCertVerify());
        $this->assertTrue($createdClient instanceof Client);
    }

    public function testSetGetEnvironment()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['createSoapClient'])
                           ->getMock()
        ;

        $soapStub = $this->createMock(Client::class);

        $selfDouble->method('createSoapClient')
                   ->willReturn($soapStub)
                   ;

        $envStub = $this->createMock(TestEnvironment::class);

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('getHeadersBuilder');
        $method->setAccessible(true);
        $this->assertNull($method->invoke($selfDouble));

        $selfDouble->setEnvironment($envStub);
        $this->assertSame($envStub, $selfDouble->getEnvironment());
        $this->assertTrue($method->invoke($selfDouble) instanceof SoapHeadersBuilder);
    }

    public function testLogin()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['handleMethod', 'getEnvironment'])
                           ->getMock()
        ;

        $selfDouble->method('handleMethod')
                    ->willReturn('123')
                    ;

        $envStub = $this->createMock(TestEnvironment::class);

        $selfDouble->method('getEnvironment')
        ->willReturn($envStub)
        ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('getSessionId');
        $method->setAccessible(true);
        $this->assertNull($method->invoke($selfDouble));

        $selfDouble->login();
        $this->assertEquals('123', $method->invoke($selfDouble));
    }

    public function testEnsureSession()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getSessionId'])
                           ->getMock()
        ;

        $selfDouble->method('getSessionId')
                    ->willReturnOnConsecutiveCalls('123', null)
                    ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('ensureSession');
        $method->setAccessible(true);
        $this->assertSame($selfDouble, $method->invoke($selfDouble));

        $this->expectException(AuthStateException::class);
        $method->invoke($selfDouble);
    }

    public function testLogout()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['getSessionId', 'handleMethod', 'ensureSession'])
                           ->getMock()
        ;

        $selfDouble->method('getSessionId')
                    ->willReturnOnConsecutiveCalls('123', null)
                    ;

        $selfDouble->method('handleMethod')
                    ->willReturnOnConsecutiveCalls('123', null)
                    ;

        $this->assertTrue($selfDouble->logout());
        $this->assertFalse($selfDouble->logout());
    }

    public function testGetCompanyDetails()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['handleMethod'])
                           ->getMock()
                           ;

        $selfDouble->method('handleMethod')
                   ->willReturn('123');

        $this->assertEquals($selfDouble->getCompanyDetails(CompanyIdType::NIP, '1234567890'), '123');
    }

    public function testGetFullReport()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['handleMethod'])
                           ->getMock()
                           ;

        $returnVal = ['irrelevant'];

        $selfDouble->method('handleMethod')
                   ->willReturn($returnVal)
                   ;

        $companyDetails = $this->createMock(CompanyDetails::class);

        $companyDetails->method('getRegon')
                       ->willReturn('12345678901234')
                       ;

        $companyDetails->method('getSiloId')
                       ->willReturn('1')
                       ;

        $this->assertEquals($selfDouble->getFullReport('BIR11JednLokalnaOsFizycznejPkd', $companyDetails), $returnVal);
    }

    public function testGetLastError()
    {
        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['handleMethod'])
                           ->getMock()
                           ;

        $returnVal = 'irrelevant';

        $selfDouble->method('handleMethod')
                   ->willReturn($returnVal)
                   ;

        $this->assertEquals($selfDouble->getLastError(), $returnVal);
    }

    public function testHandleMethod_session()
    {
        $methodHandler = $this->createMock(AbstractMethodHandler::class);

        $methodHandler->method('isSessionRequired')
        ->willReturnOnConsecutiveCalls(false, true);

        $methodHandler->method('validateResponse')
               ->willReturn(true);

        $methodHandler->method('getInputValues')
               ->willReturn([]);

        $methodHandler->method('getSoapMethodName')
               ->willReturn('test');

        $methodHandler->method('parseResponse')
               ->willReturn('123');

        $selfDouble = $this->getMockBuilder(GUSClient::class)
                           ->disableOriginalConstructor()
                           ->setMethods(['soapCall'])
                           ->getMock()
                           ;

        $selfDouble->method('soapCall')
                   ->willReturn(new stdClass())
                   ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('handleMethod');
        $method->setAccessible(true);
        $this->assertEquals('123', $method->invoke($selfDouble, $methodHandler));
        $this->expectException(AuthStateException::class);
        $method->invoke($selfDouble, $methodHandler);
    }

    public function testSoapCall_empty_no_session()
    {
        list($soapStub, $selfDouble, $method) = $this->prepareSoapCall();

        $soapStub->method('test')
        ->willReturn(null);

        $this->expectException(InvalidResponseException::class);

        $method->invoke($selfDouble, 'test', []);
    }

    public function testSoapCall_valid_no_session()
    {
        list($soapStub, $selfDouble, $method) = $this->prepareSoapCall();

        $returnVal = new \stdClass;
        $soapStub->method('test')
        ->willReturn($returnVal);

        $this->assertSame($returnVal, $method->invoke($selfDouble, 'test', []));
    }

    public function testSoapCall_valid_session()
    {
        list($soapStub, $selfDouble, $method) = $this->prepareSoapCall('123');

        $returnVal = new \stdClass;
        $soapStub->method('test')
        ->willReturn($returnVal);

        $this->assertSame($returnVal, $method->invoke($selfDouble, 'test', []));
    }

    public function testSoapCall_invvalid_no_session()
    {
        list($soapStub, $selfDouble, $method) = $this->prepareSoapCall();

        $returnVal = new \stdClass;
        $soapStub->method('test')
        ->willThrowException(new Exception('any exception'));

        $this->expectExceptionObject(new SoapCallException('any exception'));

        $this->assertSame($returnVal, $method->invoke($selfDouble, 'test', []));
    }

    protected function prepareSoapCall($sessionId = null)
    {
        $soapStub = $this->getMockBuilder(Client::class)
        ->setMethods(['test'])
        ->disableOriginalConstructor()
        ->getMock();

        $headersBuilderStub = $this->createMock(SoapHeadersBuilder::class);

        $headersBuilderStub->method('buildHeaders')
        ->willReturn([
                new \SoapHeader(SoapHeadersBuilder::ADDRESSING_NAMESPACE, 'Action', 'test_action'),
                new \SoapHeader(SoapHeadersBuilder::ADDRESSING_NAMESPACE, 'To', 'test_to'),
        ]);

        $selfDouble = $this->getMockBuilder(GUSClient::class)
        ->disableOriginalConstructor()
        ->setMethods(['getSoapClient', 'getSessionId', 'getHeadersBuilder', 'getEnvironment'])
        ->getMock()
        ;

        $envStub = $this->createMock(TestEnvironment::class);

        $selfDouble->method('getSoapClient')
                    ->willReturn($soapStub)
                    ;

        $selfDouble->method('getSessionId')
                    ->willReturn($sessionId)
                    ;

        $selfDouble->method('getHeadersBuilder')
                    ->willReturn($headersBuilderStub)
                    ;

        $selfDouble->method('getEnvironment')
                    ->willReturn($envStub)
                    ;

        $reflectedClass = new ReflectionClass(GUSClient::class);
        $method = $reflectedClass->getMethod('soapCall');
        $method->setAccessible(true);

        return [$soapStub, $selfDouble, $method];
    }
    // protected function soapCall($method, array $parameters): object
    // {
    //     $client = $this->getSoapClient();

    //     $headers = [];
    //     if ($this->getSessionId()) {
    //         $headers['sid'] = $this->getSessionId();
    //     }

    //     $client->setHeaders($headers);

    //     $soapHeaders = $this->headersBuilder->buildHeaders($this->getEnvironment(), $method);
    //     $client->__setSoapHeaders($soapHeaders);

    //     $response = null;
    //     try {
    //         $response = $client->$method($parameters);
    //     } catch (Exception $e) {
    //         throw new SoapCallException($e->getMessage(), $e->getCode());
    //     }

    //     if (empty($response)) {
    //         throw new InvalidResponseException("Empty response.", 1501);
    //     }

    //     return $response;
    // }
}
