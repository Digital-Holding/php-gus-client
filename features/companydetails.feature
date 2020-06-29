Feature: Company details retrieval
    In order to retrieve company name
    As a developer
    I need to be able to request company details by identifier


    Scenario: Retrieval of company details by NIP
    Given I am a signed GUS user
    When I request Company details identified by NIP 7740001454
    Then I should have Company details with REGON 610188201

    Scenario: Retrieval of company details by REGON
    Given I am a signed GUS user
    When I request Company details identified by REGON 610188201
    Then I should have Company details with NIP 7740001454

    Scenario: Retrieval of company details by KRS
    Given I am a signed GUS user
    When I request Company details identified by KRS 0000028860
    Then I should have Company details with NIP 7740001454