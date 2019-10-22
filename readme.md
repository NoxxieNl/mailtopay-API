# Mailtopay-API

This project aims into making the interface between the MailtoPay API and PHP easier to use.

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

## tests

Running tests with `phpunit`:
```
.\vendor\bin\phpunit
```

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **NoxxieNl** -  [NoxxieNl](https://noxxie.nl/)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
