<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;

class SearchRequest
{
    public const SORT_RANK = 'rank';
    public const SORT_TITLE = 'title';
    public const SORT_AUTHOR = 'author';
    public const SORT_DATE = 'date';

    private $params = [
        'apikey' => null,
        'vid' => null,
        'tab' => null,
        'scope' => null,
        'q' => null,
        'inst' => null
    ];

    public function __construct(
        Query $query,
        string $vid = Config::DEFAULT_VID,
        string $tab = Config::DEFAULT_TAB,
        string $scope = Config::DEFAULT_SCOPE,
        string $apikey = Config::APIKEY,
        string $inst = Config::DEFAULT_INST
    ) {
        $this->params['q'] = (string)$query;
        $this->params['apikey'] = $apikey;
        $this->params['vid'] = $vid;
        $this->params['tab'] = $tab;
        $this->params['scope'] = $scope;
        $this->params['inst'] = $inst;
    }

    public function url($version = 'v1'): string
    {
        $params = array_filter($this->params, [$this, 'isSet']);
        $facets = array_filter($this->params, [$this, 'isSet']);
        return "/primo/$version/search?" . http_build_query($params);
    }

    public function offset(int $offset)
    {
        $this->params['offset'] = (string)$offset;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->params['limit'] = (string)$limit;
        return $this;
    }

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

    public function conVoc(bool $use_controlled_vocabulary)
    {
        $this->params['conVoc'] = $use_controlled_vocabulary ? 'true' : 'false';
        return $this;
    }

    public function getMore(bool $expand_metalib_searches = false)
    {
        $this->params['getMore'] = $expand_metalib_searches ? '1' : '0';
        return $this;
    }

    public function pcAvailability(bool $show_non_fulltext = true)
    {
        $this->params['pcAvailability'] = $show_non_fulltext ? 'true' : 'false';
        return $this;
    }

    public function include(QueryFacet $facet)
    {
        if (!$facet->isExact()) {
            throw new InvalidArgumentException('qInclude facets must be exact');
        }
        $this->prepMultiParam('qInclude');
        $this->params['qInclude'] .= $facet;
        return $this;
    }

    public function exclude(QueryFacet $facet)
    {
        if (!$facet->isExact()) {
            throw new InvalidArgumentException('qExclude facets must be exact');
        }
        $this->prepMultiParam('qExclude');
        $this->params['qExclude'] .= $facet;
        return $this;
    }

    public function multiFacet(QueryFacet $facet)
    {
        if ($facet->isExact()) {
            throw new InvalidArgumentException('multiFacets facets must not be exact');
        }
        $this->prepMultiParam('multiFacets');
        $this->params['multiFacets'] .= $facet;
        return $this;
    }

    public function __toString()
    {
        return $this->url();
    }

    private function isSet($value): bool
    {
        return $value !== null;
    }

    private function prepMultiParam(string $name): void
    {
        if (!isset($this->params[$name])) {
            $this->params[$name] = '';
        } else {
            $this->params[$name] .= '|,|';
        }
    }
}