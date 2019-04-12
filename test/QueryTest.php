<?php

use BCLib\PrimoClient\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testStringIsGeneratedCorrectly(): void
    {
        $query = new Query(Query::FIELD_ANY, Query::PRECISION_BEGINS_WITH, 'otters');
        $this->assertEquals('any,begins with,otters', (string)$query);
    }

    public function testThrowExceptionForBadField(): void
    {
        $this->expectException(\BCLib\PrimoClient\Exceptions\InvalidArgumentException::class);
        $query = new Query('foo', Query::PRECISION_CONTAINS, 'baz');
    }

    public function testThrowExceptionForBadPrecision(): void
    {
        $this->expectException(\BCLib\PrimoClient\Exceptions\InvalidArgumentException::class);
        $query = new Query(Query::FIELD_ANY, 'bar', 'baz');
    }

    public function testValidFieldsAreValid(): void
    {
        $query = new Query('any', 'contains', 'foo');
        $query = new Query('title', 'contains', 'foo');
        $query = new Query('creator', 'contains', 'foo');
        $query = new Query('sub', 'contains', 'foo');
        $query = new Query('usertag', 'contains', 'foo');
        $this->assertTrue(true);
    }

    public function testValidPrecisionsAreValid(): void
    {
        $query = new Query('any', 'exact', 'foo');
        $query = new Query('any', 'contains', 'foo');
        $query = new Query('any', 'begins with', 'foo');
        $this->assertTrue(true);
    }
}
