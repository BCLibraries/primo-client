<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;

/**
 * Represents the query portion of a Brief Search API call
 *
 * Represents a single component of the query ('q') portion of a Brief Search API call.
 *
 * Casting the item as a string will return the string representation of the query for
 * inclusion in an API call
 *
 *     $param = "q=$query";
 *
 * See https://developers.exlibrisgroup.com/primo/apis/search/ for full API documentation
 * *
 * Class Query
 * @package BCLib\PrimoClient
 */
class Query
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $precision;

    /**
     * @var string
     */
    private $value;

    /**
     * Create a query
     *
     * $field and $precision can use class constants:
     *
     *     $query = new Query(Query::FIELD_ANY, Query::PRECISION_CONTAINS, 'otters')
     *
     * or bare strings:
     *
     *     $query = new Query('any', 'contains', 'otters')
     *
     * See the Primo Brief Search API documentation for details on query values.
     *
     * @param string $field
     * @param string $precision
     * @param string $value
     */
    public function __construct(string $field, string $precision, string $value)
    {
        $this->validateField($field);
        $this->validatePrecision($precision);

        $this->field = $field;
        $this->precision = $precision;
        $this->value = $value;
    }

    /**
     * Return string representation of query for inclusion in API call
     *
     * @return string
     */
    public function __toString()
    {
        $encoded_value = urlencode($this->value);
        return "{$this->field},{$this->precision},$encoded_value";
    }

    /**
     * Valid field names
     */
    public const FIELD_ANY = 'any';
    public const FIELD_TITLE = 'title';
    public const FIELD_CREATOR = 'creator';
    public const FIELD_SUBJECT = 'sub';
    public const FIELD_TAG = 'usertag';

    /**
     * Valid precision levels
     */
    public const PRECISION_EXACT = 'exact';
    public const PRECISION_CONTAINS = 'contains';
    public const PRECISION_BEGINS_WITH = 'begins with';

    private const VALID_FIELDS = [
        self::FIELD_ANY,
        self::FIELD_TITLE,
        self::FIELD_CREATOR,
        self::FIELD_SUBJECT,
        self::FIELD_TAG
    ];

    private const VALID_PRECISIONS = [
        self::PRECISION_EXACT,
        self::PRECISION_CONTAINS,
        self::PRECISION_BEGINS_WITH
    ];

    // Input validations
    private function validateField(string $field): void
    {
        if (!in_array($field, self::VALID_FIELDS, true)) {
            throw new InvalidArgumentException("$field is not a valid search field");
        }
    }

    private function validatePrecision(string $precision): void
    {
        if (!in_array($precision, self::VALID_PRECISIONS, true)) {
            throw new InvalidArgumentException("$precision is not a valid search precision");
        }
    }
}