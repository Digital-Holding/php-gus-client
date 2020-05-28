<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsPrawnaListaJednLokalnychReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsPrawnaListaJednLokalnych';
    protected const SUPPORTED_TYPE = 'P';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Lista jednostek lokalnych (np. oddziałów firmy) zarejestrowanych dla osoby prawnej.';
    protected const REGON_LEN = [ 9 ];
}
