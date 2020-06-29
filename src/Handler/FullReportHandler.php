<?php

namespace DH\GUS\Handler;

use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Model\CompanyDetails;
use DH\GUS\Model\ReportType\AbstractReportType;
use DH\GUS\Model\ReportType\ReportTypeFactory;
use InvalidArgumentException;

class FullReportHandler extends AbstractMethodHandler
{
    const NAME = 'DanePobierzPelnyRaport';
    protected const ENTITY_ID = 'pRegon';
    protected const INPUT_KEY = 'pNazwaRaportu';

    protected $companyDetails;
    protected $reportType;

    public function __construct($reportType, CompanyDetails $companyDetails)
    {
        $this->companyDetails = $companyDetails;
        $this->reportType = $reportType;

        /** @var AbstractReportType */
        $reportTypeObject = ReportTypeFactory::createReportType($reportType);
        if (!in_array(strlen($companyDetails->getRegon()), $reportTypeObject->getRegonLen())) {
            throw new InvalidArgumentException(sprintf("Regon must be %s characters long for the given report type.", join(' or ', $reportTypeObject->getRegonLen())));
        }

        if (!in_array(intval($companyDetails->getSiloId()), $reportTypeObject->getSupportedSiloIds())) {
            throw new InvalidArgumentException(sprintf("Silo Id %s is not supported for the given report type.", $companyDetails->getSiloId()));
        }
    }

    public function getInputValues()
    {
        return [
            self::ENTITY_ID => $this->companyDetails->getRegon(),
            self::INPUT_KEY => $this->reportType
        ];
    }

    public function validateResponse($response)
    {
        if (!isset($response->DanePobierzPelnyRaportResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }

        return $this;
    }

    public function parseResponse($response)
    {
        $responseParsed = @simplexml_load_string($response->DanePobierzPelnyRaportResult);

        if (!$responseParsed) {
            throw new InvalidResponseException("Failed to parse response.", 1510);
        }

        if (isset($responseParsed->dane) && !empty($responseParsed->dane)) {
            return json_decode(json_encode($responseParsed->dane), true);
        }

        return [];
    }
}
