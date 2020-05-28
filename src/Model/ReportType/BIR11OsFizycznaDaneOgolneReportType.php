<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaDaneOgolneReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaDaneOgolne';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 1, 2, 3 ];
    protected const GUS_DESCRIPTION = 'Dane osoby fizycznej wspólne dla wszystkich prowadzonych przez nią działalności.';
    protected const REGON_LEN = [ 9 ];
}
