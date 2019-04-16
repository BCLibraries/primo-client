<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\BadAPIResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;

class ApiClient
{
    /**
     * @var Client
     */
    private $guzzle;

    /**
     * ApiClient constructor.
     *
     * Create the guzzle client instance with a base_uri parameter set, e.g.
     *
     *     $guzzle = new Client(['base_uri' => $gateway_base_uri]);
     *     $api = new ApiClient($guzzle);
     *
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Send a request to the Primo API
     *
     * @param string $url
     * @return mixed
     * @throws BadAPIResponseException
     * @throws GuzzleException
     */
    public function get(string $url)
    {
        $request = new Request('GET', $url);
        try {
            $response = $this->guzzle->send($request);
        } catch (TransferException $e) {
            throw new BadAPIResponseException("Error connecting to $url : {$e->getMessage()}");
        }
        return json_decode($response->getBody()->getContents(), false);
    }
}