<?php

namespace BCLib\PrimoClient;

/**
 * Represents the query portion of a Brief Search API call
 *
 * Represents a single component of a facet ('qInclude', 'qExclude', or 'multiFacet')
 * portion of a Brief Search API call.
 *
 * Casting the item as a string will return the string representation of the query for
 * inclusion in an API call
 *
 *     $param = "qInclude=$facet1|,|$facet2";
 *
 * See https://developers.exlibrisgroup.com/primo/apis/search/ for full API documentation
 *
 * Class QueryFacet
 * @package BCLib\PrimoClient
 */
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

    /**
     * Create a facet
     *
     * $category and $operator can use class constants:
     *
     *     $facet = new Facet(Facet::CATEGORY_COLLECTION, Facet::OPERATOR_EXACT, 'Irish Journals')
     *
     * or bare strings:
     *
     *     $facet = new Facet('collection', 'exact', 'Irish Journals')
     *
     * See the Primo Brief Search API documentation for details on using facets.
     *
     * @param string $category
     * @param string $operator
     * @param string $name
     */
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

    /**
     * Return true if facet uses the exact operator
     *
     * Only exact operators are valid in qInclude or qExclude facets. Only non-exact operators are valid
     * in multiFacet facets.
     *
     * @return bool
     */
    public function isExact(): bool
    {
        return $this->operator === self::OPERATOR_EXACT;
    }

    /**
     * Return string representation of query for inclusion in API call
     *
     * @return string
     */
    public function __toString()
    {
        return "{$this->category},{$this->operator},{$this->name}";
    }

    /**
     * Valid operators
     */
    public const OPERATOR_EXACT = 'exact';
    public const OPERATOR_EXCLUDE = 'exclude';
    public const OPERATOR_INCLUDE = 'include';

    /**
     * Valid categories
     */
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