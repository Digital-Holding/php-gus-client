<?php

namespace DH\GUS\Model\ReportType;

class BIR11JednLokalnaOsFizycznejPkdReportType extends AbstractReportType
{
    protected const NAME = 'BIR11JednLokalnaOsFizycznejPkd';
    protected const SUPPORTED_TYPE = 'LF';
    protected const SUPPORTED_SILO_IDS = [ 1, 2, 3 ];
    protected const GUS_DESCRIPTION = 'Lista kodów PKD dla jednostki lokalnej podmiotu osoby fizycznej.';
    protected const REGON_LEN = [ 14 ];
}
