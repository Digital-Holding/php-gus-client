<?php

namespace DH\GUS\Handler;

abstract class AbstractMethodHandler implements MethodHandlerInterface
{
    const NAME = 'not set';
    protected const SESSION_REQUIRED = false;

    public function isSessionRequired()
    {
        return static::SESSION_REQUIRED;
    }

    public function getSoapMethodName()
    {
        return static::NAME;
    }

    abstract public function getInputValues();

    abstract public function validateResponse($response);

    abstract public function parseResponse($response);
}
