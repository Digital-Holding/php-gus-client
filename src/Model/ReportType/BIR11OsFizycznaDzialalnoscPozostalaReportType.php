<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaDzialalnoscPozostalaReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaDzialalnoscPozostala';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 3 ];
    protected const GUS_DESCRIPTION = 'Dane dot. działalności innej niż z CEIDG i rolnicza (komornik, notariusz, agroturystyka) w tym adres prowadzenia działaln.';
    protected const REGON_LEN = [ 9 ];
}
