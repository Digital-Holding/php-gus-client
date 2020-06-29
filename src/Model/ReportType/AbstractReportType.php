<?php

namespace DH\GUS\Model\ReportType;

abstract class AbstractReportType
{
    protected const NAME = 'not set';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 1, 2, 3 ];
    protected const GUS_DESCRIPTION = '';
    protected const REGON_LEN = [ 9 ];

    public function getName()
    {
        return static::NAME;
    }

    public function getSupportedSiloIds()
    {
        return static::SUPPORTED_SILO_IDS;
    }

    public function getSupportedType()
    {
        return static::SUPPORTED_TYPE;
    }

    public function getGusDescription()
    {
        return static::GUS_DESCRIPTION;
    }

    public function getRegonLen()
    {
        return static::REGON_LEN;
    }
}
