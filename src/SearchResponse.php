<?php

namespace BCLib\PrimoClient;

/**
 * A convenience class for accessing the results of a Primo Brief Search API call.
 *
 * Access can be through named parameters:
 *
 *     $total = $response->total;
 *     foreach ($response->docs as $doc) {
 *         echo "{$doc->title}\n";
 *     }
 *
 * or by accessing the JSON directly:
 *
 *     echo $response->json->info->total;
 *     foreach ($response->json->docs as $doc) {
 *         echo "{$doc->pnx->display->title[0]}\n";
 *     }
 *
 * Class SearchResponse
 * @package BCLib\PrimoClient
 *
 * @property Doc[] docs
 * @property ResponseFacet[] facets
 * @property int total
 * @property int first
 * @property int last
 * @property string did_u_mean
 * @property string controlled_vocabulary
 */
class SearchResponse
{
    use GetterSetter;

    /**
     * @var Doc[]
     */
    protected $_docs;
    protected $_facets;
    protected $_total;
    protected $_last;
    protected $_first;
    protected $_did_u_mean;
    protected $_controlled_vocabulary;

    /**
     * The original JSON of the search response in default json_decode output
     *
     * @var \stdClass
     */
    public $json;

    public function __construct(\stdClass $json)
    {
        $this->json = $json;
    }

    /**
     * @return Doc[]
     */
    public function getDocs(): array
    {
        return $this->_docs;
    }

    /**
     * @param Doc[] $docs
     */
    public function setDocs(array $docs): void
    {
        $this->_docs = $docs;
    }

    /**
     * @return ResponseFacet[]
     */
    public function getFacets(): array
    {
        return $this->_facets;
    }

    public function setFacets($facets): void
    {
        $this->_facets = $facets;
    }

    public function getTotal(): int
    {
        return $this->_total;
    }

    public function setTotal($total): void
    {
        $this->_total = $total;
    }

    public function getLast(): int
    {
        return $this->_last;
    }

    public function setLast($last): void
    {
        $this->_last = $last;
    }

    public function getFirst(): int
    {
        return $this->_first;
    }

    public function setFirst($first): void
    {
        $this->_first = $first;
    }

    public function getDidUMean(): ?string
    {
        return $this->_did_u_mean;
    }

    public function setDidUMean($did_u_mean): void
    {
        $this->_did_u_mean = $did_u_mean;
    }

    public function getControlledVocabulary(): ?string
    {
        return $this->_controlled_vocabulary;
    }

    public function setControlledVocabulary($controlled_vocabulary): void
    {
        $this->_controlled_vocabulary = $controlled_vocabulary;
    }

    public function getJson(): \stdClass
    {
        return $this->json;
    }

    public function setJson(\stdClass $json): void
    {
        $this->json = $json;
    }
}