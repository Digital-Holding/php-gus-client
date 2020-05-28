<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsFizycznaListaJednLokalnychReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsFizycznaListaJednLokalnych';
    protected const SUPPORTED_TYPE = 'F';
    protected const SUPPORTED_SILO_IDS = [ 1, 2, 3 ];
    protected const GUS_DESCRIPTION = 'Lista jednostek lokalnych (np. oddziałów zarejestrowanych dla podmiotu osoby fizycznej';
    protected const REGON_LEN = [ 9 ];
}
