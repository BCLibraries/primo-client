<?php

namespace BCLib\PrimoClient;

/**
 * Translate the JSON response from a Brief Search to a SearchResponse object
 *
 * Class SearchTranslator
 * @package BCLib\PrimoClient
 */
class SearchTranslator
{
    /**
     * Translate the JSON response from a Brief Search to a SearchResponse object
     *
     * @param \stdClass $json
     * @return SearchResponse
     */
    public static function translate(\stdClass $json): SearchResponse
    {
        $response = new SearchResponse($json);

        $response->total = $json->info->total;
        $response->first = $json->info->first;
        $response->last = $json->info->last;
        $response->did_u_mean = $json->did_u_mean ?? null;
        $response->controlled_vocabulary = isset($json->info->controlledVocabulary) ? $json->info->controlledVocabulary->errorMessages[1] : null;

        foreach ($json->facets as $facet_json) {
            $facet = FacetTranslator::translate($facet_json);
            $response->facets[$facet->name] = $facet;
        }

        $response->docs = array_map(
            function ($doc_json) {
                return DocTranslator::translate($doc_json);
            },
            $json->docs
        );

        return $response;
    }
}