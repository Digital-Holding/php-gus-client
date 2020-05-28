<?php

namespace DH\GUS\Model\ReportType;

class BIR11TypPodmiotuReportType extends AbstractReportType
{
    protected const NAME = 'BIR11TypPodmiotu';
    protected const SUPPORTED_TYPE = 'P';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Informacja o typie podmiotu (osoba fizyczna / prawna; jednostka lokalna os. fizycznej/ os. prawej).';
    protected const REGON_LEN = [ 9, 14 ];
}
