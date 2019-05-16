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
     * @param array $config
     * @param ApiClient $api_client
     * @return PrimoClient
     */
    public static function build(array $config = null, ApiClient $api_client = null): PrimoClient
    {
        if (isset($config)) {
            define(__NAMESPACE__ . '\APIKEY', $config['apikey']);
            define(__NAMESPACE__ . '\GATEWAY', $config['gateway']);
            define(__NAMESPACE__ . '\DEFAULT_VID', $config['vid']);
            define(__NAMESPACE__ . '\DEFAULT_TAB', $config['tab']);
            define(__NAMESPACE__ . '\DEFAULT_SCOPE', $config['scope']);
            if (isset($config['inst'])) {
                define('INST', $config['inst']);
            }
        }

        if ($api_client === null) {
            $guzzle = new Client(['base_uri' => GATEWAY]);
            $api_client = new ApiClient($guzzle);
        }

        return new PrimoClient($api_client);
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
        $query = new Query(Query::FIELD_ANY, Query::PRECISION_CONTAINS, $keyword);
        $request = new SearchRequest($query, DEFAULT_VID, DEFAULT_TAB, DEFAULT_SCOPE, APIKEY);
        $json = $this->client->get($request->url());
        return SearchTranslator::translate($json);
    }
}