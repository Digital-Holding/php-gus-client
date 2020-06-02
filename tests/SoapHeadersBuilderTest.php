<?php

declare(strict_types=1);

namespace DH\GUS\Tests;

use DH\GUS\Environment\EnvironmentInterface;
use PHPUnit\Framework\TestCase;
use DH\GUS\SoapHeadersBuilder;
use InvalidArgumentException;
use SoapHeader;
use SoapParam;

final class SoapHeadersBuilderTest extends TestCase
{
    public function testBuildHeaders()
    {
        $envStub = $this->createMock(EnvironmentInterface::class);
        $envStub->method('getEndpointUri')
                ->willReturn('test_endpoint')
                ;

        $this->assertTrue(!empty(SoapHeadersBuilder::ACTION));

        foreach (SoapHeadersBuilder::ACTION as $action => $methodNamespace) {
            $soapHeadersBuilder = new SoapHeadersBuilder();
            $headers = $soapHeadersBuilder->buildHeaders($envStub, $action);
            $this->assertEquals(2, count($headers));

            $this->assertTrue($headers[0] instanceof SoapHeader);
            $this->assertTrue($headers[1] instanceof SoapHeader);

            $headersAsArray = [];
            foreach ($headers as $header) {
                $headerArray = $this->convertHeaderToArray($header);
                $this->assertTrue(isset($headerArray['name']) && is_string($headerArray['name']));
                $headersAsArray[$headerArray['name']] = $headerArray;
            };

            $this->assertTrue(array_key_exists('Action', $headersAsArray));
            $this->assertTrue(array_key_exists('To', $headersAsArray));

            $this->assertEquals($headersAsArray['Action']['namespace'], SoapHeadersBuilder::ADDRESSING_NAMESPACE);
            $this->assertEquals($headersAsArray['To']['namespace'], SoapHeadersBuilder::ADDRESSING_NAMESPACE);

            $this->assertEquals($headersAsArray['Action']['data'], $methodNamespace . $action);
            $this->assertEquals($headersAsArray['To']['data'], $envStub->getEndpointUri());
        }
    }

    public function testInvalidAction()
    {
        $this->expectException(InvalidArgumentException::class);

        $envStub = $this->createMock(EnvironmentInterface::class);
        $soapHeadersBuilder = new SoapHeadersBuilder();
        $soapHeadersBuilder->buildHeaders($envStub, 'a' . mt_rand(100,999) . time());
    }

    protected function convertHeaderToArray(SoapHeader $value)
    {
        return json_decode(json_encode($value), true);
    }

}