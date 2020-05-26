<?php

namespace DH\GUS;

use DH\GUS\Environment\EnvironmentInterface;
use InvalidArgumentException;

class SoapHeadersBuilder
{
    const ADDRESSING_NAMESPACE = 'http://www.w3.org/2005/08/addressing';
    const METHODS_NAMESPACE = 'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/';
    const SERVICE_NAMESPACE = 'http://CIS/BIR/2014/07/IUslugaBIR/';

    const ACTION = [
        'GetValue' => self::SERVICE_NAMESPACE,
        'Zaloguj' => self::METHODS_NAMESPACE,
        'Wyloguj' => self::METHODS_NAMESPACE,
        'DaneSzukajPodmioty' => self::METHODS_NAMESPACE,
        'DanePobierzPelnyRaport' => self::METHODS_NAMESPACE,
        'DanePobierzRaportZbiorczy' => self::METHODS_NAMESPACE,
    ];

    public function buildHeaders(EnvironmentInterface $environment, $method)
    {
        return [
            new \SoapHeader(self::ADDRESSING_NAMESPACE, 'Action', $this->getAction($method)),
            new \SoapHeader(self::ADDRESSING_NAMESPACE, 'To', $environment->getEndpointUri()),
        ];
    }

    protected function getAction(string $method): string
    {
        if (!isset(self::ACTION[$method])) {
            throw new InvalidArgumentException(\sprintf('Action `%s` is not supported.', $method));
        }

        return self::ACTION[$method].$method;
    }
}
