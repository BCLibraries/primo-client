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
        $processed_links = [];
        foreach ($json->delivery->link as $link) {
            // Skip blank URLs
            if ($link->linkURL === '') {
                continue;
            }

            $type = str_replace('http://purl.org/pnx/linkType/', '', $link->linkType);
            $processed_links[$type] = $processed_links[$type] ?? [];
            $processed_links[$type][] = new Link($link->displayLabel, $link->linkURL, $type);
        }
        $doc->setLinks($processed_links);
        $doc->setLinkToResource($doc->links['linktorsrc'] ?? []);
        $doc->setOpenurl($doc->links['openurl'] ?? []);
        $doc->setOpenurlFulltext($doc->links['openurlfulltext'] ?? []);
        $doc->setCoverImages($processed_links['thumbnail'] ?? []);
    }

    private static function processHoldings(Doc $doc): void
    {
        // Quit early if there are no holdings.
        if (is_null($doc->json->delivery->holding)) {
            return;
        }
        $holdings = [];

        foreach ($doc->json->delivery->holding as $holding) {
            $holdings[] = new Holding(
                $holding->ilsApiId,
                $holding->libraryCode,
                $holding->subLocationCode,
                $holding->subLocation,
                $holding->callNumber,
                $holding->availabilityStatus
            );
        }
        $doc->holdings = $holdings;
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
        $addata = $doc->json->pnx->addata;
        $facets = $doc->json->pnx->facets;
        $sort = $doc->json->pnx->sort;

        $doc->id = empty($control->recordid) ? null : $control->recordid[0];
        $doc->title = empty($display->title) ? null : $display->title[0];
        $doc->date = empty($display->creationdate) ? null : $display->creationdate[0];
        $doc->publisher = empty($addata->pub) ? null : $addata->pub[0];
        $doc->abstract = empty($addata->abstract) ? null : $addata->abstract[0];
        $doc->type = empty($display->type) ? null : $display->type[0];
        $doc->mms = $display->mms ?? [];
        $doc->isbn = $addata->isbn ?? [];
        $doc->issn = $addata->issn ?? [];
        $doc->oclcid = $addata->oclcid ?? [];
        $doc->display_subject = empty($display->subject) ? null : $display->subject[0];
        $doc->format = empty($display->format) ? null : $display->format[0];
        $doc->description = $display->description ?? [];
        $doc->subjects = $display->topic ?? [];
        $doc->top_level_facets = $facets->toplevel ?? [];
        $doc->source_type = $control->sourcetype ?? [];
        $doc->is_open_access = isset($addata->oa);
        $doc->genres = $addata->genre ?? [];
        $doc->languages = $display->language ?? [];
        $doc->contributors = $display->contributor ?? [];
        $doc->cover_images = [];

        $doc->creator = empty($display->creator) ? null : $display->creator[0];

        // @todo Will these facets be available via API from VE when disableSplitFacets = false?
        $doc->creator_facet = $facets->creatorcontrib ?? [];
        $doc->collection_facet = $facets->collection ?? [];
        $doc->resourcetype_facet = $facets->rsrctype ?? [];

        $doc->sort_creator = empty($sort->author) ? null : $sort->author[0];
        $doc->sort_date = empty($sort->creationdate) ? null : $sort->creationdate[0];
        $doc->sort_title = empty($sort->title) ? null : $sort->title[0];

        $doc->is_part_of = $display->ispartof ?? [];
        $doc->journal_title = $addata->jtitle ?? [];
    }
}
