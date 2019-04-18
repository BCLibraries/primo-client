<?php

namespace BCLib\PrimoClient;

use PHPUnit\Framework\TestCase;

class DocTranslatorTest extends TestCase
{
    public function testParsesDocRecord()
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = DocTranslator::translate(json_decode($json, false));
        $this->assertEquals('The making of the Constitution.', $doc->title);
        $this->assertEquals('making of the Constitution.', $doc->sort_title);
        $this->assertEquals('1928', $doc->date);
        $this->assertEquals('Charles Warren 1868-1954', $doc->creator);
        $this->assertEquals('book', $doc->type);

        $expected_subjects = ['United States. Constitution', 'Constitutional history–United States'];
        $this->assertEquals($expected_subjects, $doc->subjects);

        $this->assertTrue($doc->is_electronic);
        $this->assertTrue($doc->is_physical);
        $this->assertFalse($doc->is_digital);
    }

    public function testParsesLinks(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = DocTranslator::translate(json_decode($json, false));

        $expected_openurl = [
            new Link('openurl', 'https://bc.userservices.exlibrisgroup.com/...openurl1', 'openurl'),
            new Link('openurl', 'https://bc.userservices.exlibrisgroup.com/...openurl2', 'openurl'),
            new Link('openurl', 'https://bc.userservices.exlibrisgroup.com/...openurl3', 'openurl'),
            new Link('openurl', 'https://bc.userservices.exlibrisgroup.com/...openurl4', 'openurl'),
        ];

        $expected_lln02 = [
            new Link('Permalink to this record', 'http://bclib.bc.edu/libsearch/bc/keyword/ALMA-BC21331257940001021',
                'lln02'),
            new Link('Permalink to this record', 'http://bclib.bc.edu/libsearch/bc/keyword/ALMA-BC21331257940001021',
                'lln02'),
            new Link('Permalink to this record', 'http://bclib.bc.edu/libsearch/bc/keyword/ALMA-BC21331257940001021',
                'lln02'),
            new Link('Permalink to this record', 'http://bclib.bc.edu/libsearch/bc/keyword/ALMA-BC21331257940001021',
                'lln02')
        ];

        $expected_linktorsrc = [
            new Link('Online Version', 'http://llmc.com/searchResultVolumes2.aspx?ext=true&catalogSet=68824',
                'linktorsrc'),
            new Link('Online Version', 'https://heinonline.org/HOL/Page?handle=hein.beal/makcon0001&collection=beal',
                'linktorsrc')
        ];

        $expected_openurlfulltext = [
            new Link('openurlfulltext', 'https://bc.userservices.exlibrisgroup.com/...fulltexturl1', 'openurlfulltext'),
            new Link('openurlfulltext', 'https://bc.userservices.exlibrisgroup.com/...fulltexturl2', 'openurlfulltext'),
            new Link('openurlfulltext', 'https://bc.userservices.exlibrisgroup.com/...fulltexturl3', 'openurlfulltext'),
            new Link('openurlfulltext', 'https://bc.userservices.exlibrisgroup.com/...fulltexturl4', 'openurlfulltext')
        ];

        $this->assertEquals($expected_lln02, $doc->links['lln02']);
        $this->assertEquals($expected_openurl, $doc->openurl);
        $this->assertEquals($expected_linktorsrc, $doc->link_to_resource);
        $this->assertEquals($expected_openurlfulltext, $doc->openurl_fulltext);
    }

    public function testParsesHoldings(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = DocTranslator::translate(json_decode($json, false));

        $expected_holdings = [
            new Holding(
                '01BC_INST21344686540001021',
                'ONL',
                'MCARD',
                'Microcard',
                '(Card)',
                'available'
            ),
            new Holding(
                '01BC_INST21344686540001021',
                'ONL',
                'STACK',
                'Stacks',
                '',
                'available'
            )
        ];
        $this->assertEquals($expected_holdings, $doc->holdings);

    }
}