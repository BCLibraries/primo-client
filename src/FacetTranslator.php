<?php

namespace BCLib\PrimoClient;

/**
 * Translate a JSON facet into a ResponseFacet object
 *
 * Class FacetTranslator
 * @package BCLib\PrimoClient
 */
class FacetTranslator
{
    /**
     * Translate a JSON facet into a ResponseFacet object
     *
     * @param \stdClass $facet_json
     * @return Doc
     */
    public static function translate(\stdClass $facet_json): ResponseFacet
    {
        $facet = new ResponseFacet($facet_json->name);
        $facet->values = array_map(
            function ($facet_val_json) {
                return new ResponseFacetValue($facet_val_json->value, $facet_val_json->count);
            },
            $facet_json->values);
        return $facet;
    }
}