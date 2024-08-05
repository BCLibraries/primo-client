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
    private $api_client;

    /**
     * @var QueryConfig
     */
    private $config;

    public function __construct(ApiClient $api_client, QueryConfig $config = null)
    {
        $this->api_client = $api_client;
        $this->config = isset($config) ? clone $config : null;
    }

    /**
     * Build a PrimoSearch client
     *
     * Provisions and builds a Primo client.
     *
     * @param string $gateway
     * @param string $apikey
     * @param string $tab
     * @param string $vid
     * @param string $scope
     * @param string|null $inst
     * @return PrimoClient
     */
    public static function build(
        string $gateway,
        string $apikey,
        string $tab,
        string $vid,
        string $scope,
        string $inst = null
    ): PrimoClient {
        $config = new QueryConfig($apikey, $tab, $vid, $scope, $inst);
        $guzzle = new Client(['base_uri' => $gateway]);
        $api_client = new ApiClient($guzzle);
        return new PrimoClient($api_client, $config);
    }

    /**
     * Perform a search
     *
     * If the $request is a string, a simple keyword search is performed. For more complicated searches, pass
     * in a SearchRequest.
     *
     * @param string|SearchRequest $request
     * @param QueryConfig|null $config
     * @return SearchResponse
     * @throws Exceptions\BadAPIResponseException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search(SearchRequest|string $request, QueryConfig $config = null): SearchResponse
    {
        $config = $config ?? $this->config;

        if (is_string($request)) {
            $query = new Query(Query::FIELD_ANY, Query::PRECISION_CONTAINS, $request);
            $request = new SearchRequest($config, $query);
        }

        $json = $this->api_client->get($request->url());
        return SearchTranslator::translate($json);
    }

    public function getSearchRequest(QueryConfig $config = null, Query $query = null): SearchRequest
    {
        $config = $config ?? $this->config;
        return new SearchRequest($config, $query);
    }
}