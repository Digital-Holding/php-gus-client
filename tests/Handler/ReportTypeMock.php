<?php

namespace DH\GUS\Tests\Handler;

use DH\GUS\Model\ReportType\AbstractReportType;

class ReportTypeMock extends AbstractReportType
{
    protected const SUPPORTED_SILO_IDS = [ 2, 3 ];
}
