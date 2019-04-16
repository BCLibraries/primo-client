<?php

namespace BCLib\PrimoClient;

use GuzzleHttp\Client;

/**
 * Search Primo using the Primo API
 *
 * Instantiate a client with the build() function and start searching:
 *
 *     $primo = PrimoClient::build();
 *     $response = $primo->search('otters');
 *
 * Class PrimoClient
 * @package BCLib\PrimoClient
 */
class PrimoClient
{
    /**
     * @var ApiClient
     */
    private $client;

    private function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Build a PrimoSearch client
     *
     * An API gateway can be specified as a parameter or taken from config.php.
     *
     * @param string $gateway_base_uri
     * @return PrimoClient
     */
    public static function build(string $gateway_base_uri = Config::GATEWAY): PrimoClient
    {
        $guzzle = new Client(['base_uri' => $gateway_base_uri]);
        $http_client = new ApiClient($guzzle);
        return new PrimoClient($http_client);
    }

    /**
     * Perform a search
     *
     * @param string $keyword
     * @return mixed
     * @throws Exceptions\BadAPIResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @todo Add more kinds of searches
     *
     */
    public function search(string $keyword)
    {
        $query = new Query(Query::FIELD_ANY, Query::PRECISION_CONTAINS, 'otters');
    }
}