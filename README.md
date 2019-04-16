# Primo Client

A client for accessing the [Primo Brief Search RESTful API](https://developers.exlibrisgroup.com/primo/apis/docs/primoSearch/R0VUIC9wcmltby92MS9zZWFyY2g=/) in PHP.

## Installation

Use [composer](https://getcomposer.org/) to install Primo-Client.

```bash
composer require bclibraries/primo-client:^0.1
```

Primo Client is currently a 0.* release, so things might change drastically with any minor release.

## Usage

```php
$primo = \BCLib\PrimoClient\PrimoClient::build();
$result = $primo->search('otters');
```

### Running tests

[PHPUnit](https://phpunit.de/) is used for testing. To run:

```bash
vendor/bin/phpunit test
```

## Contributing

1. Fork it (https://github.com/yourname/yourproject/fork)
2. Create your feature branch (git checkout -b feature/fooBar)
3. Commit your changes (git commit -am 'Add some fooBar')
4. Push to the branch (git push origin feature/fooBar)
5. Create a new Pull Request

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)