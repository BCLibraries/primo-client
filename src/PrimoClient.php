<?php

namespace BCLib\PrimoClient;

use GuzzleHttp\Client;

class PrimoClient
{
    /**
     * @var ApiClient
     */
    private $client;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
        $this->search();
    }

    public static function build(string $gateway_base_uri = Config::GATEWAY): PrimoClient
    {
        $guzzle = new Client(['base_uri' => $gateway_base_uri]);
        $http_client = new ApiClient($guzzle);
        return new PrimoClient($http_client);
    }

    /**
     * @param string $bar
     * @param mixed ...$parameters
     */
    public function search(...$parameters)
    {

    }
}