<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\BadAPIResponseException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;

class ApiClient
{
    /**
     * @var Client
     */
    private $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

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

    public function search(SearchRequest $search_request)
    {
        $uri = $search_request->url();
        return $this->get($uri);
    }
}