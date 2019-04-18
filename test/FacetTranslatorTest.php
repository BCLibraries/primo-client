<?php

namespace BCLib\PrimoClient;

use PHPUnit\Framework\TestCase;

class FacetTranslatorTest extends TestCase
{
    public function testTranslateYieldsFacet(): void
    {
        $facet_json = file_get_contents(__DIR__ . '/facet-otters-topic.json');
        $facet = FacetTranslator::translate(json_decode($facet_json, false));

        $expected_values = [
            new ResponseFacetValue('Folk dance music', 11),
            new ResponseFacetValue('Sea otter', 15),
            new ResponseFacetValue('Marine animals', 5),
            new ResponseFacetValue('Natural history', 4)
        ];

        $this->assertEquals('topic', $facet->name);
        $this->assertEquals($expected_values, $facet->values);
    }
}
