<?php

namespace DH\GUS\Model\ReportType;

class BIR11JednLokalnaOsPrawnejPkdReportType extends AbstractReportType
{
    protected const NAME = 'BIR11JednLokalnaOsPrawnejPkd';
    protected const SUPPORTED_TYPE = 'LP';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Lista kodów PKD dla jednostki lokalnej podmiotu osoby prawnej.';
    protected const REGON_LEN = [ 14 ];
}
