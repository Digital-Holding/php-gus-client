<?php

namespace DH\GUS\Environment;

use DH\Gus\Exception\EnvironmentNotFoundException;

class EnvironmentFactory
{
    protected function __construct()
    {
    }

    public static function createEnvironment($environmentName, $loginKey = null)
    {
        $environmentType = ucfirst(strtolower($environmentName));
        $className = __NAMESPACE__ . '\\' . $environmentType . 'Environment';

        if (!class_exists($className)) {
            throw new EnvironmentNotFoundException(sprintf("Class `%s` not found.", $className));
        }

        if (is_string($loginKey)) {
            return new $className($loginKey);
        }

        return new $className;
    }
}
