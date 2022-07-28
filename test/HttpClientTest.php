<?php

use BCLib\PrimoClient\Exceptions\BadAPIResponseException;
use BCLib\PrimoClient\ApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    public function testGetReturnsJSON(): void
    {
        $expected = new stdClass();
        $expected->foo = 'bar';
        $mock = new MockHandler([
            new Response(200, [], json_encode($expected))
        ]);
        $handler = HandlerStack::create($mock);
        $http_client = new ApiClient(new Client(['handler' => $handler]));
        $response = $http_client->get('/');


        $this->assertEquals($expected, $response);
    }


    public function testBadRequestThrowsException(): void
    {
        $this->expectException(BadAPIResponseException::class);
        $bad_request = new Request('GET', '/test');
        $bad_response = new Response();
        $mock = new MockHandler([
            new BadResponseException('test', $bad_request, $bad_response)
        ]);
        $handler = HandlerStack::create($mock);
        $http_client = new ApiClient(new Client(['handler' => $handler]));
        $response = $http_client->get('/');
    }
}
