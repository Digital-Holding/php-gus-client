<?php

namespace DH\GUS\Handler;

interface MethodHandlerInterface
{
    public function isSessionRequired();

    public function getSoapMethodName();

    public function getInputValues();

    public function validateResponse($response);

    public function parseResponse($response);
}
