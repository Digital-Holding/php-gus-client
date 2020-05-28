<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaDzialalnoscCeidgReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaDzialalnoscCeidg';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 1 ];
    protected const GUS_DESCRIPTION = 'Dane dot. działalności zarejestrowanej w CEIDG, w tym adres prowadzenia tej działalności.';
    protected const REGON_LEN = [ 9 ];
}
