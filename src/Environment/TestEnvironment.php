<?php

namespace DH\GUS\Environment;

class TestEnvironment implements EnvironmentInterface
{
    const ENDPOINT = 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc';
    const WSDL = 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl-ver11-test.wsdl';
    const TEST_LOGIN_KEY = 'abcde12345abcde12345';

    public function getLoginKey(): ?string
    {
        return self::TEST_LOGIN_KEY;
    }

    public function getEndpointUri(): ?string
    {
        return self::ENDPOINT;
    }

    public function getWsdl(): ?string
    {
        return self::WSDL;
    }

    public function getIgnoreSsl(): ?bool
    {
        return true;
    }
}
