<?php

namespace DH\GUS;

use DH\GUS\Environment\EnvironmentFactory;

class GUSClientFactory
{
    protected function __construct()
    {
    }

    public static function createWithEnvironment($environmentName, $loginKey = null) : GUSClient
    {
        $environment = EnvironmentFactory::createEnvironment($environmentName, $loginKey);

        return new GUSClient($environment);
    }
}
