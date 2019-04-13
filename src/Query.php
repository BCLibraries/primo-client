<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;

class Query
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var
     */
    private $precision;

    /**
     * @var
     */
    private $value;

    public function __construct(string $field, string $precision, string $value)
    {
        $this->validateField($field);
        $this->validatePrecision($precision);

        $this->field = $field;
        $this->precision = $precision;
        $this->value = $value;
    }

    public function __toString()
    {
        return "{$this->field},{$this->precision},{$this->value}";
    }

    public const FIELD_ANY = 'any';
    public const FIELD_TITLE = 'title';
    public const FIELD_CREATOR = 'creator';
    public const FIELD_SUBJECT = 'sub';
    public const FIELD_TAG = 'usertag';

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