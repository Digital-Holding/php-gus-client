<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use DH\GUS\CompanyIdType;
use DH\GUS\GUSClient;
use DH\GUS\GUSClientFactory;
use DH\GUS\Model\CompanyDetails;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /** @var GUSClient */
    protected $gusClient;

    /** @var CompanyDetails */
    protected $companyDetails;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->gusClient = GUSClientFactory::createWithEnvironment('test');
    }

    /**
     * @Given I am a signed GUS user
     */
    public function iAmASignedGusUser()
    {
        $this->gusClient->login();
    }

    /**
     * @When I request Company details identified by NIP :nip
     */
    public function iRequestCompanyDetailsIdentifiedByNip($nip)
    {
        $this->companyDetails = null;
        $data = $this->gusClient->getCompanyDetails(CompanyIdType::NIP, $nip);
        if (!isset($data[$nip])) {
            throw new Exception('Company details not found.');
        }
        $this->companyDetails = $data[$nip];
    }

    /**
     * @Then I should have Company details with REGON :arg1
     */
    public function iShouldHaveCompanyDetailsWithRegon($regon)
    {
        if (!$this->companyDetails instanceof CompanyDetails || $this->companyDetails->getRegon() !== $regon) {
            throw new Exception('Failed to verify company details.');
        }
    }

    /**
     * @When I request Company details identified by REGON :regon
     */
    public function iRequestCompanyDetailsIdentifiedByRegon($regon)
    {
        $data = $this->gusClient->getCompanyDetails(CompanyIdType::REGON, $regon);
        if (empty($data)) {
            throw new Exception('Company details not found.');
        }

        $this->companyDetails = current($data);
    }

    /**
     * @Then I should have Company details with NIP :nip
     */
    public function iShouldHaveCompanyDetailsWithNip($nip)
    {
        if (!$this->companyDetails instanceof CompanyDetails || $this->companyDetails->getNip() !== $nip) {
            throw new Exception('Failed to verify company details.');
        }
    }

    /**
     * @When I request Company details identified by KRS :krs
     */
    public function iRequestCompanyDetailsIdentifiedByKrs($krs)
    {
        $data = $this->gusClient->getCompanyDetails(CompanyIdType::KRS, $krs);
        if (empty($data)) {
            throw new Exception('Company details not found.');
        }

        $this->companyDetails = current($data);
    }
}
