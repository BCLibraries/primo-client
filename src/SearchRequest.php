<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;

/**
 * A Primo Brief Search API request
 *
 * Represents a single Brief Search API request. Most public methods are setters corresponding
 * to an API query parameter and can be chained:
 *
 *     $request->limit(20)
 *             ->offset(3)
 *             ->exclude($language_is_english)
 *             ->exclude($date_is_after_1900)
 *             ->sort(SearchRequest::SORT_DATE);
 *
 * Casting the item as a string or calling url() will return URL of the search from the path
 * forward, e.g.:
 *
 *     $url = "https://api-na.hosted.exlibrisgroup.com/{$request}";
 *
 * See https://developers.exlibrisgroup.com/primo/apis/search/ for full API documentation
 *
 * Class SearchRequest
 * @package BCLib\PrimoClient
 */
class SearchRequest
{

    /**
     * Possible values for sort order. Can be sed with sort() function.
     */
    public const SORT_RANK = 'rank';
    public const SORT_TITLE = 'title';
    public const SORT_AUTHOR = 'author';
    public const SORT_DATE = 'date';

    /**
     * Stores URL query string parameters. Key is parameter name, value is the string representation of
     * parameter value as used in the Primo REST API.
     *
     * @var array
     */
    private $params = [];

    public function __construct(
        Query $query,
        string $vid,
        string $tab,
        string $scope,
        string $apikey,
        string $inst = null
    ) {
        $this->params['apikey'] = $apikey;
        $this->params['vid'] = $vid;
        $this->params['tab'] = $tab;
        $this->params['scope'] = $scope;
        $this->params['q'] = (string)$query;
        $this->params['inst'] = $inst;
    }

    /**
     * Get the request's URL
     *
     * @param string $version e.g. ("v1")
     * @return string the URL minus the protocol & host
     */
    public function url($version = 'v1'): string
    {
        $params = array_filter($this->params, [$this, 'isSet']);
        return "/primo/$version/search?" . http_build_query($params);
    }

    /**
     * Set the request offset
     *
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset)
    {
        $this->params['offset'] = (string)$offset;
        return $this;
    }

    /**
     * Set the search limit
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->params['limit'] = (string)$limit;
        return $this;
    }

    /**
     * Set the sort order
     *
     * Can use a SORT_* constant:
     *
     *     $req->sort(SearchRequest::SORT_TITLE);
     *
     * or bare string:
     *
     *     $req->sort('title');
     *
     * @param string $sort order code
     * @return $this
     */
    public function sort(string $sort)
    {
        $valid_sorts = [
            self::SORT_AUTHOR,
            self::SORT_DATE,
            self::SORT_RANK,
            self::SORT_TITLE
        ];

        if (!in_array($sort, $valid_sorts, true)) {
            throw new InvalidArgumentException("$sort is not valid sort");
        }

        $this->params['sort'] = $sort;
        return $this;
    }

    /**
     * Use Primo's controlled vocabulary searches
     *
     * conVoc searches expand some phrases to use synonyms, e.g. "heart attack" will also search for
     * "myocardial infarction".
     *
     * @param bool $use_controlled_vocabulary
     * @return $this
     */
    public function conVoc(bool $use_controlled_vocabulary)
    {
        $this->params['conVoc'] = $use_controlled_vocabulary ? 'true' : 'false';
        return $this;
    }

    /**
     * Expand results in MetaLib searches
     *
     * @param bool $expand_metalib_searches
     * @return $this
     */
    public function getMore(bool $expand_metalib_searches = false)
    {
        $this->params['getMore'] = $expand_metalib_searches ? '1' : '0';
        return $this;
    }

    /**
     * Display non-fulltext Primo Central results?
     *
     * @param bool $show_non_fulltext true to show non-fulltext results
     * @return $this
     */
    public function pcAvailability(bool $show_non_fulltext = true)
    {
        $this->params['pcAvailability'] = $show_non_fulltext ? 'true' : 'false';
        return $this;
    }

    /**
     * Filter results to include those matching facet
     *
     * Multiple facets can be added in chain:
     *
     *     $req->include($creator_is_mark_twain)
     *         ->include($type_is_book);
     *
     * Applies a logical AND between facets, so only results matching all facets will be included.
     *
     * @param QueryFacet $facet
     * @return $this
     */
    public function include(QueryFacet $facet)
    {
        if (!$facet->isExact()) {
            throw new InvalidArgumentException('qInclude facets must be exact');
        }
        $this->prepMultiParam('qInclude');
        $this->params['qInclude'] .= $facet;
        return $this;
    }

    /**
     * Filter results to exclude those matching facet
     *
     * Multiple facets can be added in chain:
     *
     *     $req->exclude($date_is_after_1900)
     *         ->exclude($type_is_microform);
     *
     * Applies a logical AND between facets, so only results matching all facets will be excluded.
     *
     * @param QueryFacet $facet
     * @return $this
     */
    public function exclude(QueryFacet $facet)
    {
        if (!$facet->isExact()) {
            throw new InvalidArgumentException('qExclude facets must be exact');
        }
        $this->prepMultiParam('qExclude');
        $this->params['qExclude'] .= $facet;
        return $this;
    }

    /**
     * Filter results using multiple possible values
     *
     * Unlike exclude and include, multiFacet facets apply a logical OR between facet values.
     * For example, to filter to books in English or French:
     *
     *     $req->multiFacet($in_english)
     *         ->multiFacet($in_french);
     *
     * Logical AND is applied between facet categories, so:
     *
     *     $req->multiFacet($in_english)
     *         ->multiFacet($in_french)
     *         ->multiFacet($written_after_1900);
     *
     * would only result in items in English or French written after 1900.
     *
     * @param QueryFacet $facet
     * @return $this
     */
    public function multiFacet(QueryFacet $facet)
    {
        if ($facet->isExact()) {
            throw new InvalidArgumentException('multiFacets facets must not be exact');
        }
        $this->prepMultiParam('multiFacets');
        $this->params['multiFacets'] .= $facet;
        return $this;
    }

    /**
     * Get the request's URL
     *
     * Alias for url()
     *
     * @return string
     */
    public function __toString()
    {
        return $this->url();
    }

    private function isSet($value): bool
    {
        return $value !== null;
    }

    /**
     * Prepare multi-parameter fields for stringification
     *
     * Primo joins multi-parameters using '|,|'. If a field already has parameters in it,
     * append this string to the end of the existing parameter.
     *
     * @param string $name
     */
    private function prepMultiParam(string $name): void
    {
        if (!isset($this->params[$name])) {
            $this->params[$name] = '';
        } else {
            $this->params[$name] .= '|,|';
        }
    }
}