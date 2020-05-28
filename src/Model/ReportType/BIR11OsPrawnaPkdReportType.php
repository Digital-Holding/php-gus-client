<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsPrawnaPkdReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsPrawnaPkdReport';
    protected const SUPPORTED_TYPE = 'P';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Lista kodów PKD dla podmiotu osoby prawnej.';
    protected const REGON_LEN = [ 9 ];
}
