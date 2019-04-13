<?php

use BCLib\PrimoClient\Exceptions\InvalidArgumentException as InvalidArgumentExceptionAlias;
use BCLib\PrimoClient\Query;
use BCLib\PrimoClient\QueryFacet;
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

        $this->request = new SearchRequest($query, $vid, $tab, $scope, $apikey);

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
        $this->expectException(InvalidArgumentExceptionAlias::class);
        $this->request->sort('NOTASORT');
    }

    public function testSettersAreFluent(): void
    {
        $f1 = $this->createMock(QueryFacet::class);
        $f1->method('isExact')
            ->willReturn(true);

        $f2 = $this->createMock(QueryFacet::class);
        $f2->method('isExact')
            ->willReturn(false);

        $return = $this->request->conVoc(false)
            ->getMore(true)
            ->pcAvailability(false)
            ->offset(12)
            ->limit(4)
            ->sort(SearchRequest::SORT_DATE)
            ->include($f1)
            ->exclude($f1)
            ->multiFacet($f2);
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

    public function testAddingNonExactIncludeFacetThrowsException(): void
    {
        $this->expectException(InvalidArgumentExceptionAlias::class);
        $facet = $this->createMock(QueryFacet::class);
        $facet->method('isExact')
            ->willReturn(false);
        $this->request->include($facet);
    }

    public function testAddingNonExactExcludeFacetThrowsException(): void
    {
        $this->expectException(InvalidArgumentExceptionAlias::class);
        $facet = $this->createMock(QueryFacet::class);
        $facet->method('isExact')
            ->willReturn(false);
        $this->request->include($facet);
    }

    public function testAddingExactMultiFacetThrowsException(): void
    {
        $this->expectException(InvalidArgumentExceptionAlias::class);
        $facet = $this->createMock(QueryFacet::class);
        $facet->method('isExact')
            ->willReturn(true);
        $this->request->multiFacet($facet);
    }

    public function testIncludeFacetSetCorrectly(): void
    {
        $f1 = $this->createMock(QueryFacet::class);
        $f1->method('isExact')->willReturn(true);
        $f1->method('__toString')->willReturn('facet1');
        $this->request->include($f1);
        $expected = $this->expected_url. '&qInclude=facet1';
        $this->assertEquals($expected, $this->request->url());

        $f2 = $this->createMock(QueryFacet::class);
        $f2->method('isExact')->willReturn(true);
        $f2->method('__toString')->willReturn('facet2');
        $this->request->include($f2);
        $expected = $this->expected_url. '&qInclude=facet1%7C%2C%7Cfacet2';
        $this->assertEquals($expected, $this->request->url());
    }

    public function testMultiFacetSetCorrectly(): void
{
    $f1 = $this->createMock(QueryFacet::class);
    $f1->method('isExact')->willReturn(false);
    $f1->method('__toString')->willReturn('facet1');
    $this->request->multiFacet($f1);
    $expected = $this->expected_url. '&multiFacets=facet1';
    $this->assertEquals($expected, $this->request->url());

    $f2 = $this->createMock(QueryFacet::class);
    $f2->method('isExact')->willReturn(false);
    $f2->method('__toString')->willReturn('facet2');
    $this->request->multiFacet($f2);
    $expected = $this->expected_url. '&multiFacets=facet1%7C%2C%7Cfacet2';
    $this->assertEquals($expected, $this->request->url());
}
}
