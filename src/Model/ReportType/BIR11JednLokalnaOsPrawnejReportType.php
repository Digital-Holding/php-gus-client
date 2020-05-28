<?php

namespace DH\GUS\Model\ReportType;

class BIR11JednLokalnaOsPrawnejReportType extends AbstractReportType
{
    protected const NAME = 'BIR11JednLokalnaOsPrawnej';
    protected const SUPPORTED_TYPE = 'LP';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Dane jednostki lokalnej podmiotu osoby prawnej.';
    protected const REGON_LEN = [ 14 ];
}
