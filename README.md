# Primo Client

A client for accessing the [Primo Brief Search RESTful API](https://developers.exlibrisgroup.com/primo/apis/docs/primoSearch/R0VUIC9wcmltby92MS9zZWFyY2g=/) in PHP.

## Installation

Use [composer](https://getcomposer.org/) to install Primo-Client.

```bash
composer require bclibraries/primo-client:^0.3
```

Primo Client is currently a 0.* release, so things might change drastically with any minor release.

## Usage

Create a configuration hash and pass it to `PrimoClient::build()` to 
instantiate a client.

```php
require_once __DIR__.'/vendor/autoload.php';

$config = [
    'apikey' => 'l7xx38c6a1a3043974262e81a81fb7475ba9',
    'gateway' => 'https://api-na.hosted.exlibrisgroup.com',
    'vid' => 'my_vid',
    'tab' => 'the_tab',
    'scope' => 'mylib'
];
$primo = \BCLib\PrimoClient\PrimoClient::build(
                                                $config['gateway'],
                                                $config['apikey'], 
                                                $config['tab'],
                                                $config['vid'],
                                                $config['scope']
                                               );
$response = $primo->search('otters');
```

Passing a string to `search()` will perform a simple keyword search. For more complex searches, pass in a
`SearchRequest` object:

```php
$request = $primo->getSearchRequest();

$contains_manchurian_candidate = new \BCLib\PrimoClient\Query('any','contains','manchurian candidate');
$contains_demme = new \BCLib\PrimoClient\Query('creator','contains','demme');

$is_video = new \BCLib\PrimoClient\QueryFacet('facet_rtype','exact','video');

$request->addQuery($contains_manchurian_candidate)
    ->addQuery($contains_demme, 'NOT')
    ->include($is_video)
    ->sort('date')
    ->limit(5);

$response = $primo->search($request);
```

The JSON structure of a SearchResponse can be accessed directly:

```php
echo "{$response->json->info->total} total results\n";
foreach ($response->json->docs as $doc) {
    echo "{$doc->pnx->display->title[0]}\n";
}
```

or through convenience parameters:

```php
echo "{$response->total} total results\n";
foreach ($response->docs as $doc) {
    echo "{$doc->title}\n";
}
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