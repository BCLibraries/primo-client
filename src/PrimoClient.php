<?php

namespace BCLib\PrimoClient;

use GuzzleHttp\Client;

class PrimoClient
{
    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    public static function build(string $gateway_base_uri = Config::GATEWAY): PrimoClient
    {
        $guzzle = new Client(['base_uri' => $gateway_base_uri]);
        $http_client = new HttpClient($guzzle);
        return new PrimoClient($http_client);
    }

    public function search(SearchRequest $search_request)
    {
        $uri = $search_request->url();
        $json = $this->client->get($uri);
    }
}