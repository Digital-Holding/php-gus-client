<?php

namespace DH\GUS\Handler;

use DH\Gus\Exception\InvalidResponseException;

class LogoutHandler extends AbstractMethodHandler
{
    const NAME = 'Wyloguj';
    protected const SESSION_REQUIRED = true;

    protected const INPUT_KEY = 'pIdentyfikatorSesji';

    protected $sessionId;

    public function __construct($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    public function getInputValues()
    {
        return [
            self::INPUT_KEY => $this->sessionId
        ];
    }

    public function validateResponse($response)
    {
        if (!isset($response->WylogujResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }
    }

    public function parseResponse($response)
    {
        return $response->WylogujResult === true;
    }
}
