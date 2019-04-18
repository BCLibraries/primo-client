<?php

namespace BCLib\PrimoClient;

use PHPUnit\Framework\TestCase;

class SearchTranslatorTest extends TestCase
{
    public function testReadsInfoCorrectly(): void
    {
        $json_file = file_get_contents(__DIR__ . '/search-otters.json');
        $response = SearchTranslator::translate(json_decode($json_file, false));
        $this->assertEquals(96, $response->total);
        $this->assertEquals(10, $response->last);
        $this->assertEquals(1, $response->first);
        $this->assertEquals('others', $response->did_u_mean);
    }

    public function testReadsFacetsCorrectly(): void
    {
        $json_file = file_get_contents(__DIR__ . '/search-otters.json');
        $response = SearchTranslator::translate(json_decode($json_file, false));

        $expected_keys = [
            'creator',
            'lang',
            'rtype',
            'topic',
            'tlevel',
            'pfilter',
            'creationdate',
            'genre',
            'library',
            'newrecords',
            'local1',
            'local4'
        ];
        $this->assertEquals($expected_keys, array_keys($response->facets));
        $this->assertContainsOnlyInstancesOf('BCLib\PrimoClient\ResponseFacet', $response->facets);
    }

    public function testReadsDocsCorrectly(): void
    {
        $json_file = file_get_contents(__DIR__ . '/search-otters.json');
        $response = SearchTranslator::translate(json_decode($json_file, false));

        $this->assertCount(10, $response->docs);
        $this->assertContainsOnlyInstancesOf('BCLib\PrimoClient\Doc', $response->docs);
    }
}
