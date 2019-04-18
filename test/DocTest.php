<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DocTest extends TestCase
{
    public function testCallingInvalidPNXCategoryThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = new Doc(json_decode($json, false));
        $doc->pnx('foo', 'bar');
    }

    public function testCallingUnsetPNXReturnsEmptyArray(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = new Doc(json_decode($json, false));
        $this->assertEquals([], $doc->pnx('display', 'lds31'));
    }

    public function testCallingMultiItemPNXFieldReturnsKeyedArray(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = new Doc(json_decode($json, false));
        $expected = [
            'ALMA-BC21331257940001021' => '01BC_INST:21331257940001021',
            'ALMA-BC51460206020001021' => '01BC_INST:51460206020001021',
            'ALMA-BC51421060810001021' => '01BC_INST:51421060810001021',
            'ALMA-BC51502186130001021' => '01BC_INST:51502186130001021'
        ];
        $this->assertEquals($expected, $doc->pnx('control', 'almaid'));
    }

    public function testCustomPNXFieldReturned(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-supreme+court.json');
        $doc = new Doc(json_decode($json, false));
        $this->assertEquals(['little brown and company'], $doc->pnx('dedup', 'f10'));
    }

    public function testPCIRecordsWork(): void
    {
        $json = file_get_contents(__DIR__ . '/doc-pci-otters.json');
        $doc = new Doc(json_decode($json, false));
        $this->assertTrue(true);
    }
}
