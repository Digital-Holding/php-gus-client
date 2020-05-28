<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaDzialalnoscSkreslonaDo20141108ReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaDzialalnoscSkreslonaDo20141108';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 4 ];
    protected const GUS_DESCRIPTION = 'Dane dot. działalności skreślonej z REGON przed 2014.11.08 (tzn. w poprzednim systemie informatycznym).';
    protected const REGON_LEN = [ 9 ];
}
