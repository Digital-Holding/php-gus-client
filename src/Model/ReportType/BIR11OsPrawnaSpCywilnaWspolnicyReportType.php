<?php

namespace DH\GUS\Model\ReportType;

class BIR11OsPrawnaSpCywilnaWspolnicyReportType extends AbstractReportType
{
    protected const NAME = 'BIR11OsPrawnaSpCywilnaWspolnicy';
    protected const SUPPORTED_TYPE = 'P';
    protected const SUPPORTED_SILO_IDS = [ 6 ];
    protected const GUS_DESCRIPTION = 'Lista wspólników spółki cywilnej (tylko i wyłącznie S.C.) Uwaga: REGON prowadzi rejestrację wspólników od r.2012. (Dla spółek powstałych przed r.2012 i nieaktualizowanych - brak jest w REGON danych o wspólnikach).';
    protected const REGON_LEN = [ 9 ];
}
