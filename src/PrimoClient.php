<?php

namespace BCLib\PrimoClient;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

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

    public function search(SearchRequest $search_request)
    {
        $uri = GATEWAY . $search_request->url();
        $json = $this->client->sendRequest($uri);
    }
}