<?php

namespace DH\GUS\Model;

use DateTime;
use SimpleXMLElement;

class CompanyDetails
{
    const COMPANY_TYPE = [
        'F' => 'natural_person',
        'P' => 'juridical_person',
        'LF' => 'natural_person_local_representation',
        'LP' => 'juridical_person_local_representation'
    ];

    const SILO_ID_TYPE = [
        '2' => 'agriculture_business_address',
        '3' => 'special_business_external_address',
        '4' => 'natural_person_address_removed_pre_2014',
        '6' => 'juridical_person_main_address',
        '1' => 'natural_person_main_address'
    ];

    protected $nip;
    protected $regon;
    protected $nipStatus;
    protected $name;
    protected $voivodeship;
    protected $district;
    protected $municipality;
    protected $city;
    protected $postcode;
    protected $street;
    protected $houseNumber;
    protected $flatNumber;
    protected $type;
    protected $siloId;
    protected $closedAt;
    protected $postCity;

    public function __construct(SimpleXMLElement $rawInputData)
    {
        $this->nip = $rawInputData->Nip ? preg_replace("/[^0-9]/", "", (string) $rawInputData->Nip) : null;
        $this->regon = $rawInputData->Regon ? preg_replace("/[^0-9]/", "", (string) $rawInputData->Regon) : null;
        $this->nipStatus = $rawInputData->StatusNip ? (string) $rawInputData->StatusNip : null;
        $this->name = $rawInputData->Nazwa ? (string) $rawInputData->Nazwa : null;
        $this->voivodeship = $rawInputData->Wojewodztwo ? (string) $rawInputData->Wojewodztwo : null;
        $this->district = $rawInputData->Powiat ? (string) $rawInputData->Powiat : null;
        $this->municipality = $rawInputData->Gmina ? (string) $rawInputData->Gmina : null;
        $this->city = $rawInputData->Miejscowosc ? (string) $rawInputData->Miejscowosc : null;
        $this->postcode = $rawInputData->KodPocztowy ? (string) $rawInputData->KodPocztowy : null;
        $this->street = $rawInputData->Ulica ? (string) $rawInputData->Ulica : null;
        $this->houseNumber = $rawInputData->NrNieruchomosci ? (string) $rawInputData->NrNieruchomosci : null;
        $this->flatNumber = $rawInputData->NrLokalu ? (string) $rawInputData->NrLokalu : null;
        $this->type = $rawInputData->Typ ? (string) $rawInputData->Typ : null;
        $this->siloId = $rawInputData->SilosID ? (string) $rawInputData->SilosID : null;
        $this->closedAt = isset($rawInputData->DataZakonczeniaDzialalnosci) && !empty($rawInputData->DataZakonczeniaDzialalnosci) ? new DateTime($rawInputData->DataZakonczeniaDzialalnosci) : null;
        $this->postCity = $rawInputData->MiejscowoscPoczty ? (string) $rawInputData->MiejscowoscPoczty : null;
    }

    public function getNip()
    {
        return $this->nip;
    }

    public function getRegon()
    {
        return $this->regon;
    }

    public function getNipStatus()
    {
        return $this->nipStatus;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVoivodeship()
    {
        return $this->voivodeship;
    }

    public function getDistrict()
    {
        return $this->district;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getMunicipality()
    {
        return $this->municipality;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    public function getFlatNumber()
    {
        return $this->flatNumber;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTypeTranslated()
    {
        return self::COMPANY_TYPE[$this->getType()];
    }

    public function getSiloId()
    {
        return $this->siloId;
    }

    public function getSiloIdTranslated()
    {
        return self::SILO_ID_TYPE[$this->getSiloId()];
    }

    public function getClosedAt()
    {
        return $this->closedAt;
    }

    public function getPostCity()
    {
        return $this->postCity;
    }
}
