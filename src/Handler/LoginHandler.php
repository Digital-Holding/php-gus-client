<?php

namespace DH\GUS\Handler;

use DH\GUS\Environment\EnvironmentInterface;
use DH\Gus\Exception\AuthStateException;
use DH\Gus\Exception\InvalidResponseException;

class LoginHandler extends AbstractMethodHandler
{
    const NAME = 'Zaloguj';
    protected const INPUT_KEY = 'pKluczUzytkownika';

    protected $loginKey;

    public function __construct(EnvironmentInterface $environment)
    {
        $this->loginKey = $environment->getLoginKey();
    }

    public function getInputValues()
    {
        return [
            self::INPUT_KEY => $this->loginKey
        ];
    }

    public function validateResponse($response)
    {
        if (!isset($response->ZalogujResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }
    }

    public function parseResponse($response)
    {
        $sessionId = $response->ZalogujResult;

        if (empty($sessionId)) {
            throw new AuthStateException("Login failure.", 1503);
        }

        return $sessionId;
    }
}
