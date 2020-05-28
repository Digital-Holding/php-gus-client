<?php

namespace DH\GUS\Model\ReportType;

use DH\Gus\Exception\ReportTypeNotFoundException;

class ReportTypeFactory
{
    protected function __construct()
    {
    }

    public static function createReportType($reportTypeName)
    {
        $className = __NAMESPACE__ . '\\' . $reportTypeName . 'ReportType';

        if (!class_exists($className)) {
            throw new ReportTypeNotFoundException(sprintf("Class `%s` not found.", $className));
        }

        return new $className;
    }
}
