<?php

namespace BCLib\PrimoClient;

/**
 * Translate JSON docs into Doc objects
 *
 * Class DocTranslator
 * @package BCLib\PrimoClient
 */
class DocTranslator
{
    /**
     * Translate a JSON doc into a Doc object
     *
     * @param \stdClass $json
     * @return Doc
     */
    public static function translate(\stdClass $json): Doc
    {
        $doc = new Doc($json);

        self::determineTypes($doc);
        self::processPNX($doc);
        self::processLinks($doc);
        self::processHoldings($doc);

        return $doc;
    }

    private static function processLinks(Doc $doc): void
    {
        $json = $doc->json;
        foreach ($json->delivery->link as $link) {
            $type = str_replace('http://purl.org/pnx/linkType/', '', $link->linkType);
            $doc->links[$type] = $doc->links[$type] ?? [];
            $doc->links[$type][] = new Link($link->displayLabel, $link->linkURL, $type);
        }
        $doc->link_to_resource = $doc->links['linktorsrc'];
        $doc->openurl = $doc->links['openurl'];
        $doc->openurl_fulltext = $doc->links['openurlfulltext'];
    }

    private static function processHoldings(Doc $doc): void
    {
        $json = $doc->json;
        foreach ($json->delivery->holding as $holding) {
            $doc->holdings[] = new Holding(
                $holding->ilsApiId,
                $holding->libraryCode,
                $holding->subLocationCode,
                $holding->subLocation,
                $holding->callNumber,
                $holding->availabilityStatus
            );
        }
    }

    private static function determineTypes(Doc $doc): void
    {
        $doc->is_electronic = in_array('Alma-E', $doc->json->delivery->deliveryCategory, true);
        $doc->is_digital = in_array('Alma-D', $doc->json->delivery->deliveryCategory, true);
        $doc->is_physical = in_array('Alma-P', $doc->json->delivery->deliveryCategory, true);
    }

    private static function processPNX(Doc $doc): void
    {
        $control = $doc->json->pnx->control;
        $display = $doc->json->pnx->display;
        $search = $doc->json->pnx->search;
        $addata = $doc->json->pnx->addata;
        $facets = $doc->json->pnx->facets;
        $sort = $doc->json->pnx->sort;

        $doc->id = empty($control->recordid) ? null : $control->recordid[0];
        $doc->title = empty($display->title) ? null : $display->title[0];
        $doc->date = empty($display->creationdate) ? null : $display->creationdate[0];
        $doc->publisher = empty($addata->pub) ? null : $addata->pub[0];
        $doc->abstract = empty($addata->abstract) ? null : $addata->abstract[0];
        $doc->type = empty($display->type) ? null : $display->type[0];
        $doc->isbn = $search->isbn ?? [];
        $doc->issn = $search->issn ?? [];
        $doc->oclcid = $addata->oclcid ?? [];
        $doc->display_subject = empty($display->subject) ? null : $display->subject[0];
        $doc->format = empty($display->format) ? null : $display->format[0];
        $doc->description = $display->description ?? [];
        $doc->subjects = $facets->topic ?? [];
        $doc->genres = $facets->genre ?? [];
        $doc->languages = $facets->language ?? [];
        $doc->contributors = $display->contributor ?? [];
        $doc->cover_images = [];

        $doc->creator = empty($display->creator) ? null : $display->creator[0];

        $doc->creator_facet = $facets->creatorcontrib ?? [];
        $doc->collection_facet = $facets->collection ?? [];
        $doc->resourcetype_facet = $facets->rsrctype ?? [];

        $doc->sort_creator = empty($sort->author) ? null : $sort->author[0];
        $doc->sort_date = empty($sort->creationdate) ? null : $sort->creationdate[0];
        $doc->sort_title = empty($sort->title) ? null : $sort->title[0];
    }
}