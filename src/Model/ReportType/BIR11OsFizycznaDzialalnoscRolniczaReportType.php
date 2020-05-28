<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaDzialalnoscRolniczaReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaDzialalnoscRolnicza';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 2 ];
    protected const GUS_DESCRIPTION = 'Dane dot. działalności rolniczej; w tym adres prowadzenia działalności rolniczej.';
    protected const REGON_LEN = [ 9 ];
}
