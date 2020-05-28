php-gus-client
==============

PHP Client library for Główny Urząd Statystyczny (Polish Central Statistical Office, known also as "Statistics Poland").

Allows (fairly) easy interaction with the webservices of GUS which are described in detailed on their websites:
<https://api.stat.gov.pl/Home/RegonApi>

# Installation

It is suggested to use `composer` in your project which allows automatic installation, to do so use:
```
composer require dh/php-gus-client
```

Yet you can of course still manually include **all** of the files in your project (that is why composer is suggested
as it automatically autoloads all required classes).

# Installation in Symfony

Thanks to the provided factories it is possible to use the library easily with Symfony 4+, just add to your `services.yaml`:

```yaml
    dh.service.gusclient:
        factory: ['DH\GUS\GUSClientFactory', createWithEnvironment]
        class: DH\GUS\GUSClient
        arguments:
            - "production"
            - "your login key"
```

Where first argument must be `production` or `test`. For `production` you need to provide your **login key** obtained from
GUS as the second argument.

For `test` environment no additional arguments are required as default login key for testing purposes is used.

# Supported operations

## Login

```php
$client->login();
```

Attempts to sign in using your login key. Most other methods require you to be signed in.

## Logout

```php
$client->logout();
```

Destroys your previously created session.

## Retrieve companies' details
```php
$client->getCompanyDetails(CompanyIdType::NIP, ['7740001454', '7642542255']);
```

Where first argument defined the identifier type and second argument should be a string or an array of up to 20 strings
which are values of the selected identifier type.

Method returns **always** an **array** of `CompanyDetails` instances by their **NIP** identifiers.

## Retrieve last error

In case of an error method `getLastError` returns its description (in Polish). **May be empty even if an error occurred
in the `test` environment.

## Retrieve Full report

```php
$client->getFulLReport('BIR11OsFizycznaDaneOgolne', $companyDetails['7642542255']);
```

Retrieves a full report in a form of an array for the given `CompanyDetails`.

**Warning:** library does not expose a way to retrieve a report by NIP, REGON or KRS because it verifies first if provided
`CompanyDetails` are compatbile with the provided report type in terms of supported `type` and `silo id`.

Full list of returned fields for each report type can be found in the folder `BIR11_StrukturyDanych` of the documentation
on GUS' website.

First argument must be a name of supported report type. Supported report types are defined in `DH\GUS\Model\ReportType` namespace.

# TODO

* method to retrieve all supported report types
* supported report types by `CompanyDetails`
* implementation of the `DanePobierzRaportZbiorczy` call
* service status call implementation
* automatic login if session was expired
* session re-use (session adapter)
* phpDocs
* unit tests