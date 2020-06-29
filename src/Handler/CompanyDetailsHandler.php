<?php

namespace DH\GUS\Handler;

use DH\GUS\CompanyIdType;
use DH\GUS\Exception\InvalidResponseException;
use DH\GUS\Model\CompanyDetails;
use InvalidArgumentException;

class CompanyDetailsHandler extends AbstractMethodHandler
{
    const NAME = 'DaneSzukajPodmioty';

    const ID_TYPE_MAPPING_SINGLE = [
        CompanyIdType::NIP => 'Nip',
        CompanyIdType::REGON => 'Regon',
        CompanyIdType::KRS => 'Krs'
    ];

    const ID_TYPE_MAPPING_PLURAL = [
        CompanyIdType::NIP => 'Nipy',
        CompanyIdType::REGON => 'Regony',
        CompanyIdType::KRS => 'Krs',
        CompanyIdType::REGON . '9' => 'Regony9zn',
        CompanyIdType::REGON . '14' => 'Regony14zn',
    ];
    protected const SESSION_REQUIRED = true;
    protected const INPUT_KEY = 'pParametryWyszukiwania';

    protected $paramType;
    protected $value;

    public function __construct($paramType, $value)
    {
        $this->paramType = $this->validateAndEstablishParamType($paramType, $value);
        $this->value = is_array($value) ? join(',', $value) : $value;
    }

    public function getInputValues()
    {
        return [
            self::INPUT_KEY => [
                $this->paramType => $this->value
            ]
        ];
    }

    public function validateResponse($response)
    {
        if (!isset($response->DaneSzukajPodmiotyResult)) {
            throw new InvalidResponseException("Missing required attributes in the response.", 1502);
        }

        return $this;
    }

    public function parseResponse($response)
    {
        $responseParsed = @simplexml_load_string($response->DaneSzukajPodmiotyResult);

        if (!$responseParsed) {
            throw new InvalidResponseException("Failed to parse response.", 1510);
        }

        $results = [];
        if (isset($responseParsed->dane)) {
            foreach ($responseParsed->dane as $companyRawData) {
                $companyParsed = new CompanyDetails($companyRawData);
                $results[$companyParsed->getNip()] = $companyParsed;
            }
        }

        return $results;
    }

    protected function validateAndEstablishParamType($paramType, $paramValue):string
    {
        $values = [];
        if (!is_array($paramValue)) {
            $values[] = $paramValue;
        } else {
            $values = $paramValue;
        }

        if (empty($values)) {
            throw new InvalidArgumentException('At least one value is required.');
        }

        if (count($values) > 20) {
            throw new InvalidArgumentException('Maximum supported values count is 20.');
        }

        $previousLen = null;
        foreach ($values as $value) {
            switch ($paramType) {
                case CompanyIdType::NIP:
                    if (!preg_match('/^[0-9]{10}$/', $value)) {
                        throw new InvalidArgumentException("NIP must be a string of exactly 10 digits.", 1600);
                    }
                break;
                case CompanyIdType::KRS:
                    if (!preg_match('/^[0-9]{10}$/', $value)) {
                        throw new InvalidArgumentException("KRS must be a string of exactly 10 digits.", 1600);
                    }
                break;
                case CompanyIdType::REGON:
                    if (!preg_match('/^[0-9]{9}([0-9]{5})?$/', $value)) {
                        throw new InvalidArgumentException("REGON must be a string of exactly 9 or 14 digits.", 1600);
                    }

                    $len = strlen($value);
                    if ($previousLen !== null && $previousLen != $len) {
                        throw new InvalidArgumentException("All REGON numbers must be same length in a single query (9 or 14).", 1600);
                    }
                    $previousLen = $len;
                break;
            }
        }

        if (!is_array($paramValue)) {
            return self::ID_TYPE_MAPPING_SINGLE[$paramType];
        } elseif ($paramType === CompanyIdType::REGON) {
            return self::ID_TYPE_MAPPING_PLURAL[$paramType . $previousLen];
        } else {
            return self::ID_TYPE_MAPPING_PLURAL[$paramType];
        }
    }
}
