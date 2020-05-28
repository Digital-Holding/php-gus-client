<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsPrawnaReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsPrawna';
    protected const SUPPORTED_TYPE = 'P';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Dane osoby prawnej; Uwaga: raporty dla osoby prawnej dot. także spółki cywilnej, która formalnie osobą prawną nie jest.';
    protected const REGON_LEN = [ 9 ];
}
