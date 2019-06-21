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
 */
class SearchResponse
{
    /**
     * @var Doc[]
     */
    public $docs;
    public $facets;
    public $total;
    public $last;
    public $first;
    public $did_u_mean;
    public $controlled_vocabulary;

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
}