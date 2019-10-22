# Mailtopay-API

This package aims into making the interface between the MailtoPay API and PHP easier to use. This package can only be used with version 2 of the Mailtopay APi, version 1 is not supported and is deprecated by Mailtopay.

More information regarding the Mailtopay API can be found on the Mailtopay [website](https://mailtopay.nl).

**This project is in open beta, please report any bugs using the issue tracker.**

## Requirements

* PHP >= 5.3.7

## Installation
```
composer require noxxie/mailtopay-api
```

After installation you can use any given `endpoint` class and use the `client` class to do the request.
See the [docs](docs/) directory for more information regarding the endpoints.

## Usage

See the [docs](docs/) directory for information for each endpoint. You can use the `client` class to do the actuall request.
````php
use Noxxie\Mailtopay\Client;
...

$client = new Client('id', 'passphrase', 'base_uri', $endpoint);
$response = $client->execute();
````

The `id`, `passphrase` and `base_uri` are provided information from Mailtopay.

### Validation

This package aims to validate any provided data as much as possible in order to give back invalid that where possible before the request is send to the API. When a invalid parameter is set a `InvalidParameterException` exception will be thrown.

However it is always possible that you enter some wierd combination of data that this package does not validate for. The API also does a validation check and when a error rises from the API an error response is returned. You can pick those up with the `ResponseException` exception.

## tests

Running tests with `phpunit`
```
.\vendor\bin\phpunit
```

## Versioning

We use [SemVer](http://semver.org/) for versioning.

## Authors

* NoxxieNl -  [Noxxie.nl](https://noxxie.nl/)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
