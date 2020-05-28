<?php

namespace DH\GUS\Handler;

use DH\GUS\Exception\InvalidResponseException;

class LastErrorHandler extends AbstractMethodHandler
{
    const NAME = 'GetValue';
    protected const SESSION_REQUIRED = true;

    protected const INPUT_KEY = 'pNazwaParametru';
    protected const INPUT_VALUE = 'StanDanych';

    public function getInputValues()
    {
        return [
            self::INPUT_KEY => self::INPUT_VALUE
        ];
    }

    public function validateResponse($response)
    {
        if (!isset($response->GetValueResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }
    }

    public function parseResponse($response)
    {
        var_dump($response);

        return $response->GetValueResult;
    }
}
