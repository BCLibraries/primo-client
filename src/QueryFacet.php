<?php

namespace BCLib\PrimoClient;

class QueryFacet
{
    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $name;

    public function __construct(string $category, string $operator, string $name)
    {
        if (!in_array($category, self::VALID_CATEGORIES, true)) {
            throw new Exceptions\InvalidArgumentException("$category is not a valid facet category");
        }

        if (!in_array($operator, self::VALID_OPERATORS, true)) {
            throw new Exceptions\InvalidArgumentException("$operator is not a valid facet operator");
        }

        $this->category = $category;
        $this->operator = $operator;
        $this->name = $name;
    }

    public function isExact(): bool
    {
        return $this->operator === self::OPERATOR_EXACT;
    }

    public function __toString()
    {
        return "{$this->category},{$this->operator},{$this->name}";
    }

    public const OPERATOR_EXACT = 'exact';
    public const OPERATOR_EXCLUDE = 'exclude';
    public const OPERATOR_INCLUDE = 'include';

    public const CATEGORY_AUTHOR = 'facet_creator';
    public const CATEGORY_AVAILABILITY = 'facet_tlevel';
    public const CATEGORY_COLLECTION = 'facet_domain';
    public const CATEGORY_LANGUAGE = 'facet_lang';
    public const CATEGORY_LIBRARY_NAME = 'facet_library';
    public const CATEGORY_LCC_CLASS = 'facet_lcc ';
    public const CATEGORY_RESOURCE_TYPE = 'facet_rtype';
    public const CATEGORY_SUBJECT = 'facet_topic';

    private const VALID_CATEGORIES = [
        self::CATEGORY_AUTHOR,
        self::CATEGORY_AVAILABILITY,
        self::CATEGORY_COLLECTION,
        self::CATEGORY_LANGUAGE,
        self::CATEGORY_LCC_CLASS,
        self::CATEGORY_LIBRARY_NAME,
        self::CATEGORY_RESOURCE_TYPE,
        self::CATEGORY_SUBJECT
    ];

    private const VALID_OPERATORS = [self::OPERATOR_EXACT, self::OPERATOR_EXCLUDE, self::OPERATOR_INCLUDE];
}