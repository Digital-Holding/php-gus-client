<?php

namespace DH\GUS;

use DateTimeZone;
use DH\GUS\Environment\EnvironmentInterface;
use DH\GUS\Exception\AuthStateException;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Exception\SoapCallException;
use DH\GUS\Handler\CompanyDetailsHandler;
use DH\GUS\Handler\FullReportHandler;
use DH\GUS\Handler\LastErrorHandler;
use DH\GUS\Handler\LoginHandler;
use DH\GUS\Handler\LogoutHandler;
use DH\GUS\Handler\MethodHandlerInterface;
use DH\GUS\Model\CompanyDetails;
use Exception;
use IDCT\Networking\Soap\Client;

class GUSClient
{
    protected const SOAP_OPTIONS = [
        'soap_version' => SOAP_1_2,
        'trace' => true,
        'style' => SOAP_DOCUMENT
    ];
    protected $headersBuilder;
    protected $environment;
    protected $soapClient;
    protected $sessionId;

    protected static $gusTimeZone;

    public function __construct(EnvironmentInterface $environment)
    {
        $this->setEnvironment($environment);
    }

    public function setEnvironment(EnvironmentInterface $environment) : self
    {
        $this->environment = $environment;
        $this->soapClient = $this->createSoapClient($environment);
        $this->headersBuilder = new SoapHeadersBuilder();

        return $this;
    }

    public function getEnvironment(): ?EnvironmentInterface
    {
        return $this->environment;
    }

    public function logout(): bool
    {
        if ($this->handleMethod(new LogoutHandler($this->getSessionId()))) {
            $this->sessionId = null;

            return true;
        }

        return false;
    }

    public static function getGusTimeZone()
    {
        if (static::$gusTimeZone === null) {
            static::$gusTimeZone = new DateTimeZone('Europe/Warsaw');
        }

        return static::$gusTimeZone;
    }

    public function login(): self
    {
        $this->sessionId = $this->handleMethod(new LoginHandler($this->getEnvironment()));

        return $this;
    }

    public function getCompanyDetails($paramType, $paramValue)
    {
        return $this->handleMethod(new CompanyDetailsHandler($paramType, $paramValue));
    }

    public function getFullReport($reportType, CompanyDetails $companyDetails)
    {
        return $this->handleMethod(new FullReportHandler($reportType, $companyDetails));
    }

    public function getLastError()
    {
        return $this->handleMethod(new LastErrorHandler());
    }

    protected function getSoapOptions()
    {
        return $this::SOAP_OPTIONS;
    }

    protected function createSoapClient(EnvironmentInterface $environment)
    {
        $options = $this->getSoapOptions();
        $wsdl = $environment->getWsdl();
        $client = new Client($wsdl, $options, 30, 3, 30);
        $client->setIgnoreCertVerify($environment->getIgnoreSsl())
               ->__setLocation($environment->getEndpointUri())
               ;

        return $client;
    }

    protected function getHeadersBuilder()
    {
        return $this->headersBuilder;
    }

    protected function handleMethod(MethodHandlerInterface $method)
    {
        if ($method->isSessionRequired()) {
            $this->ensureSession();
        }

        $response = $this->soapCall($method->getSoapMethodName(), $method->getInputValues());
        $method->validateResponse($response);

        return $method->parseResponse($response);
    }

    protected function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    protected function ensureSession(): self
    {
        if (!$this->getSessionId()) {
            throw new AuthStateException("Session id not set, please log in first.", 1500);
        }

        return $this;
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

        $client->setHeaders($headers);

        $soapHeaders = $this->getHeadersBuilder()->buildHeaders($this->getEnvironment(), $method);
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
}
