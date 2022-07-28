<?php

namespace BCLib\PrimoClient;

use PHPUnit\Framework\TestCase;

class ResponseFacetTest extends TestCase
{
    /**
     * @var ResponseFacet
     */
    public $facet;

    public function setUp():void
    {
        $this->facet = new ResponseFacet('topic');
        $this->facet->values = [
            new ResponseFacetValue('Folk dance music', 11),
            new ResponseFacetValue('Sea otter', 15),
            new ResponseFacetValue('Marine animals', 5),
            new ResponseFacetValue('Natural history', 4)
        ];
    }

    public function testSortAlphabetically(): void
    {
        $expected =[
            new ResponseFacetValue('Folk dance music', 11),
            new ResponseFacetValue('Marine animals', 5),
            new ResponseFacetValue('Natural history', 4),
            new ResponseFacetValue('Sea otter', 15)
        ];
        $this->facet->sortAlphabetically();
        $this->assertEquals($expected, $this->facet->values);
    }

    public function testSortByFrequency(): void
    {
        $expected =[
            new ResponseFacetValue('Sea otter', 15),
            new ResponseFacetValue('Folk dance music', 11),
            new ResponseFacetValue('Marine animals', 5),
            new ResponseFacetValue('Natural history', 4)
        ];
        $this->facet->sortByFrequency();
        $this->assertEquals($expected, $this->facet->values);
    }
}
