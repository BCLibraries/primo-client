<?php

namespace BCLib\PrimoClient;

use BCLib\PrimoClient\Exceptions\InvalidArgumentException;

/**
 * A single Doc as returned by the Brief Search API
 *
 * Doc is a convenience class for accessing fields in a Brief Search doc record. It
 * some important record fields without requiring knowledge of the JSON record
 * structure.
 *
 * Access can be through named parameters:
 *
 *     $title = $doc->title;
 *     foreach ($doc->link_to_resource as $link) {
 *         $outbound[] = $link->url;
 *     }
 *
 * through general access of arbitrary PNX fields:
 *
 *    foreach ($doc->pnx('display', 'lds11') as $mms) {
 *         echo "MMS: $mms\n";
 *    }
 *
 * or by accessing the JSON directly:
 *
 *     foreach ($doc->json->display->lds11 as $mms) {
 *         echo "MMS: $mms\n";
 *    }
 *
 * Class Doc
 * @package BCLib\PrimoClient
 */
class Doc
{
    /**
     * Raw JSON of the doc result, in json_decode default output format.
     *
     * @var \stdClass
     */
    public $json;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $creator;

    /**
     * @var string[]
     */
    public $contributors = [];

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $publisher;

    /**
     * @var string
     */
    public $abstract;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string[]
     */
    public $isbn = [];

    /**
     * @var string[]
     */
    public $issn = [];

    /**
     * @var string[]
     */
    public $oclcid = [];

    /**
     * @var string[]
     */
    public $subjects = [];

    /**
     * @var string
     */
    public $display_subject;

    /**
     * @var string[]
     */
    public $genres = [];

    /**
     * @var string[]
     */
    public $creator_facet = [];

    /**
     * @var string[]
     */
    public $collection_facet = [];

    /**
     * @var string[]
     */
    public $resourcetype_facet = [];

    /**
     * @var string[]
     */
    public $languages = [];

    /**
     * @var string
     */
    public $format;

    /**
     * @var string[]
     */
    public $description;

    /**
     * @var string
     */
    public $frbr_group_id;

    /**
     * @var string[]
     */
    public $cover_images;

    /**
     * @var Link[]
     */
    public $link_to_resource;

    /**
     * @var Link[]
     */
    public $openurl;

    /**
     * @var Link[]
     */
    public $openurl_fulltext;

    /**
     * @var string
     */
    public $sort_title;

    /**
     * @var string
     */
    public $sort_creator;

    /**
     * @var string
     */
    public $sort_date;

    /**
     * @var bool
     */
    public $is_electronic;

    /**
     * @var bool
     */
    public $is_physical;

    /**
     * @var bool
     */
    public $is_digital;

    /**
     * @var array[]
     */
    public $links;

    /**
     * @var Holding[]
     */
    public $holdings;

    /**
     * Doc constructor.
     *
     * @param \stdClass $doc_json doc JSON, as output by json_decode
     */
    public function __construct(\stdClass $doc_json)
    {
        $this->json = $doc_json;
    }

    /**
     * Return value of an arbitrary PNX field
     *
     * PNX fields are returned as arrays. For most PNX fields, this is a simple list, e.g.:
     *
     *     ['little brown and company', 'scribner', 'new york times']
     *
     * For some fields in deduplicated records, Primo encodes the relevant holding ID
     * in the PNX value. For these fields, the return array is keyed with the holding ID,
     * e.g.:
     *
     *     [
     *        'ALMA-BC21331257940001021' => '01BC_INST:21331257940001021',
     *        'ALMA-BC51460206020001021' => '01BC_INST:51460206020001021',
     *        'ALMA-BC51421060810001021' => '01BC_INST:51421060810001021',
     *        'ALMA-BC51502186130001021' => '01BC_INST:51502186130001021'
     *     ]
     *
     * @param string $category
     * @param string $field
     * @return array
     */
    public function pnx(string $category, string $field)
    {
        if (!isset($this->json->pnx->$category)) {
            throw new InvalidArgumentException("$category is not a valid PNX category");
        }

        if (empty($this->json->pnx->$category->$field)) {
            return [];
        }

        $result = $this->json->pnx->$category->$field;

        if (preg_match('/^\$\$V(.*)\$\$O(.*)$/', $result[0])) {
            $result = $this->readMultiItemPNXEntry($result);
        }

        return $result;
    }

    private function readMultiItemPNXEntry($pnx_array): array
    {
        $result = [];
        foreach ($pnx_array as $item) {
            preg_match('/^\$\$V(.*)\$\$O(.*)$/', $item, $matches);
            $result[$matches[2]] = $matches[1];
        }
        return $result;
    }
}