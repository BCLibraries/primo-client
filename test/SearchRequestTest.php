<?php

use BCLib\PrimoClient\Query;
use BCLib\PrimoClient\SearchRequest;
use PHPUnit\Framework\TestCase;

class SearchRequestTest extends TestCase
{
    /**
     * @var SearchRequest
     */
    private $request;

    /**
     * @var string
     */
    private $expected_url;

    public function setUp()
    {
        $apikey = 'l7xx38c6a1a3043974262e81a81fb7475ba9';
        $vid = 'bclib';
        $tab = 'default';
        $scope = 'bcl';

        $query = $this->createMock(Query::class);
        $query->method('__toString')
            ->willReturn('any,contains,otters');

        $this->request = new SearchRequest($apikey,
            $vid,
            $tab,
            $scope,
            $query);

        $query_output = 'any%2Ccontains%2Cotters';
        $this->expected_url = "/primo/v1/search?apikey=$apikey&vid=$vid&tab=$tab&scope=$scope&q=$query_output";
    }

    public function testBasicRequestProducesCorrectURL(): void
    {
        $this->assertEquals($this->expected_url, $this->request->url());
    }

    public function testSetsControlledVocabulary(): void
    {
        $expected_url = "{$this->expected_url}&conVoc=false";
        $this->request->conVoc(false);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testsSetsGetMore(): void
    {
        $expected_url = "{$this->expected_url}&getMore=1";
        $this->request->getMore(true);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testsSetsPCAvailability(): void
    {
        $expected_url = "{$this->expected_url}&pcAvailability=false";
        $this->request->pcAvailability(false);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testSetsOffset(): void
    {
        $expected_url = "{$this->expected_url}&offset=12";
        $this->request->offset(12);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testSetLimit(): void
    {
        $expected_url = "{$this->expected_url}&limit=4";
        $this->request->limit(4);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testSetsSort(): void
    {
        $expected_url = "{$this->expected_url}&sort=rank";
        $this->request->sort(SearchRequest::SORT_RANK);
        $this->assertEquals($expected_url, $this->request->url());
    }

    public function testBadSortThrowsException(): void
    {
        $this->expectException(\BCLib\PrimoClient\Exceptions\InvalidArgumentException::class);
        $this->request->sort('NOTASORT');
    }

    public function testSettersAreFluent(): void
    {
        $return = $this->request->conVoc(false)
            ->getMore(true)
            ->pcAvailability(false)
            ->offset(12)
            ->limit(4)
            ->sort(SearchRequest::SORT_DATE);
        $this->assertEquals($this->request, $return);
    }

    public function testVersionSetsCorrectly(): void
    {
        $expected = '/primo/v2/search';
        $url = $this->request->url('v2');
        $this->assertEquals($expected, substr($url, 0, 16));
    }

    public function testToStringReturnsURL(): void
    {
        $this->assertEquals($this->request->url(), (string)$this->request);
    }
}
