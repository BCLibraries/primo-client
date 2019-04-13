<?php

use BCLib\PrimoClient\QueryFacet;
use PHPUnit\Framework\TestCase;

class QueryFacetTest extends TestCase
{
    public function testThrowsExceptionOnBadCategory()
    {
        $this->expectException(\BCLib\PrimoClient\Exceptions\InvalidArgumentException::class);
        $facet = new QueryFacet('facet_fake', QueryFacet::OPERATOR_INCLUDE, 'foo');
    }

    public function testThrowsExceptionOnBadOperator()
    {
        $this->expectException(\BCLib\PrimoClient\Exceptions\InvalidArgumentException::class);
        $facet = new QueryFacet(QueryFacet::CATEGORY_SUBJECT, 'facet_fake', 'foo');
    }

    public function testDeterminesIfFacetIsExact()
    {
        $f1 = new QueryFacet(QueryFacet::CATEGORY_SUBJECT, QueryFacet::OPERATOR_EXACT, 'foo');
        $this->assertTrue($f1->isExact());

        $f2 = new QueryFacet(QueryFacet::CATEGORY_SUBJECT, QueryFacet::OPERATOR_INCLUDE, 'foo');
        $this->assertFalse($f2->isExact());
    }

    public function testOutpuIsCorrect()
    {
        $facet = new QueryFacet(QueryFacet::CATEGORY_SUBJECT, QueryFacet::OPERATOR_EXACT, 'foo');
        $expected = 'facet_topic,exact,foo';
        $this->assertEquals($expected, (string)$facet);
    }
}
