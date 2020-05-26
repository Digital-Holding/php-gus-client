<?php

namespace DH\GUS\Environment;

use DH\Gus\Exception\InvalidResponseException;

class ProductionEnvironment implements EnvironmentInterface
{
    const ENDPOINT = 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc';
    const WSDL = 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl-ver11.wsdl';
    private $loginKey;

    public function __construct($loginKey)
    {
        if (empty($loginKey) || !is_string($loginKey)) {
            throw new InvalidResponseException("Login key is required.", 1499);
        }

        $this->loginKey = $loginKey;
    }

    public function getLoginKey(): ?string
    {
        return $this->loginKey;
    }

    public function getEndpointUri(): ?string
    {
        return self::ENDPOINT;
    }

    public function getWsdl(): ?string
    {
        return self::WSDL;
    }

    public function getIgnoreSsl()
    {
        return false;
    }
}
