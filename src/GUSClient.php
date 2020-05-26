<?php

namespace DH\GUS;

use DH\GUS\Environment\EnvironmentInterface;
use DH\GUS\Exception\AuthStateException;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Exception\SoapCallException;
use Exception;
use IDCT\Networking\Soap\Client;
use InvalidArgumentException;
use SoapClient;

class GUSClient
{
    protected $headersBuilder;
    protected $environment;
    protected $soapClient;
    protected $sessionId;

    public function __construct(EnvironmentInterface $environment = null)
    {
        if ($environment) {
            $this->setEnvironment($environment);
        }

        $options = [
            'soap_version' => SOAP_1_2,
            'trace' => true,
            'style' => SOAP_DOCUMENT
        ];

        $this->soapClient = new Client($environment->getWsdl(), $options, 30, 3, 30);
        $this->getSoapClient()->__setLocation($environment->getEndpointUri());
        $this->getSoapClient()->setIgnoreCertVerify($environment->getIgnoreSsl());

        $this->headersBuilder = new SoapHeadersBuilder();
    }

    public function setEnvironment(EnvironmentInterface $environment) : self
    {
        $this->environment = $environment;

        return $this;
    }

    public function getEnvironment(): ?EnvironmentInterface
    {
        return $this->environment;
    }

    public function logout(): bool
    {
        $this->ensureSession();

        /** @var SoapClient */
        $response = $this->soapCall('Wyloguj', [
            'pIdentyfikatorSesji' => $this->getSessionId()
        ]);

        if (!isset($response->WylogujResponse) || !isset($response->WylogujResponse->WylogujResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }

        if ($response->WylogujResponse->WylogujResult === true) {
            $this->sessionId = null;

            return true;
        }

        return false;
    }

    public function login(): self
    {
        $environment = $this->getEnvironment();
        $loginkey = $environment->getLoginKey();

        $response = $this->soapCall('Zaloguj', [
            'pKluczUzytkownika' => $loginkey
        ]);

        if (!isset($response->ZalogujResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }

        $sessionId = $response->ZalogujResult;

        if (empty($sessionId)) {
            throw new AuthStateException("Login failure.", 1503);
        }

        $this->sessionId = $sessionId;

        return $this;
    }

    public function getCompanyDetails(SearchParamTypeEnum $paramType, $paramValue)
    {
        $this->ensureSession();
        $finalParamType = $this->validateAndEstablishParamType($paramType, $paramValue);
        $finalValue = is_array($paramValue) ? join(',', $paramValue) : $paramValue;

        $response = $this->soapCall('Zaloguj', [
            'pParametryWyszukiwania' => [
                $finalParamType => $finalValue
            ]
        ]);

        if (!isset($response->DaneSzukajPodmiotyResponse) || !isset($response->DaneSzukajPodmiotyResponse->DaneSzukajPodmiotyResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }
    }

    protected function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    protected function ensureSession(): void
    {
        if (!$this->getSessionId()) {
            throw new AuthStateException("Session id not set, please log in first.", 1500);
        }
    }

    protected function getSoapClient(): Client
    {
        return $this->soapClient;
    }

    protected function soapCall($method, array $parameters): object
    {
        $client = $this->getSoapClient();

        $headers = [];
        if ($this->getSessionId()) {
            $headers['sid'] = $this->getSessionId();
        }

        $soapHeaders = $this->headersBuilder->buildHeaders($this->getEnvironment(), $method);
        $client->__setSoapHeaders($soapHeaders);

        $response = null;
        try {
            $response = $client->$method($parameters);
        } catch (Exception $e) {
            throw new SoapCallException($e->getMessage(), $e->getCode());
        }

        if (empty($response)) {
            throw new InvalidResponseException("Empty response.", 1501);
        }

        return $response;
    }

    protected function validateAndEstablishParamType(SearchParamTypeEnum $paramType, $paramValue):string
    {
        $values = [];
        if (!is_array($paramValue)) {
            $values[] = $paramValue;
        }

        $previousLen = null;
        foreach ($values as $value) {
            switch ($paramType) {
                case SearchParamTypeEnum::NIP:
                    if (!preg_match('/[0-9]{10}/', $value)) {
                        throw new InvalidArgumentException("NIP must be a string of exactly 10 digits.", 1600);
                    }
                break;
                case SearchParamTypeEnum::KRS:
                    if (!preg_match('/[0-9]{10}/', $value)) {
                        throw new InvalidArgumentException("KRS must be a string of exactly 10 digits.", 1600);
                    }
                break;
                case SearchParamTypeEnum::REGON:
                    if (!preg_match('/[0-9]{9}([0-9]{5})?/', $value)) {
                        throw new InvalidArgumentException("REGON must be a string of exactly 9 or 14 digits.", 1600);
                    }

                    $len = strlen($value);
                    if ($previousLen !== null && $previousLen != $len) {
                        throw new InvalidArgumentException("All REGON numbers must be same length in a single query (9 or 14).", 1600);
                    }
                    $previousLen = $len;
                break;
            }
        }

        if (!is_array($paramValue)) {
            if ($paramType === SearchParamTypeEnum::NIP) {
                return 'Nip';
            }

            if ($paramType === SearchParamTypeEnum::KRS) {
                return 'Krs';
            }

            if ($paramType === SearchParamTypeEnum::REGON) {
                return 'Regon';
            }
        } else {
            if ($paramType === SearchParamTypeEnum::NIP) {
                return 'Nipy';
            }

            if ($paramType === SearchParamTypeEnum::KRS) {
                return 'Krsy';
            }

            if ($paramType === SearchParamTypeEnum::REGON && $previousLen === 9) {
                return 'Regony9zn';
            } else {
                return 'Regony14zn';
            }
        }
    }
}
