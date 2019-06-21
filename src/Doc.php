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
 *
 * @property string id
 * @property string title
 * @property string creator
 * @property string[] contributors
 * @property string date
 * @property string publisher
 * @property string abstract
 * @property string type
 * @property string[] isbn
 * @property string[] issn
 * @property string[] oclcid
 * @property string display_subject
 * @property string[] genres
 * @property string[] creator_facet
 * @property string[] collection_facet
 * @property string[] resourcetype_facet
 * @property string[] languages
 * @property string format
 * @property string[] description
 * @property string frbr_group_id
 * @property string[] cover_images
 * @property Link[] openurl
 * @property Link[] openurl_fulltext
 * @property string sort_title
 * @property string sort_creator
 * @property bool is_electronic
 * @property bool is_physical
 * @property bool is_digital
 * @property array links
 * @property Holding[] holdings
 *
 * @package BCLib\PrimoClient
 */
class Doc
{
    use GetterSetter;

    /**
     * Raw JSON of the doc result, in json_decode default output format.
     *
     * @var \stdClass
     */
    public $json;

    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_title;

    /**
     * @var string
     */
    protected $_creator;

    /**
     * @var string[]
     */
    protected $_contributors = [];

    /**
     * @var string
     */
    protected $_date;

    /**
     * @var string
     */
    protected $_publisher;

    /**
     * @var string
     */
    protected $_abstract;

    /**
     * @var string
     */
    protected $_type;

    /**
     * @var string[]
     */
    protected $_isbn = [];

    /**
     * @var string[]
     */
    protected $_issn = [];

    /**
     * @var string[]
     */
    protected $_oclcid = [];

    /**
     * @var string[]
     */
    protected $_subjects = [];

    /**
     * @var string
     */
    protected $_display_subject;

    /**
     * @var string[]
     */
    protected $_genres = [];

    /**
     * @var string[]
     */
    protected $_creator_facet = [];

    /**
     * @var string[]
     */
    protected $_collection_facet = [];

    /**
     * @var string[]
     */
    protected $_resourcetype_facet = [];

    /**
     * @var string[]
     */
    protected $_languages = [];

    /**
     * @var string
     */
    protected $_format;

    /**
     * @var string[]
     */
    protected $_description;

    /**
     * @var string
     */
    protected $_frbr_group_id;

    /**
     * @var string[]
     */
    protected $_cover_images;

    /**
     * @var Link[]
     */
    protected $_link_to_resource;

    /**
     * @var Link[]
     */
    protected $_openurl;

    /**
     * @var Link[]
     */
    protected $_openurl_fulltext;

    /**
     * @var string
     */
    protected $_sort_title;

    /**
     * @var string
     */
    protected $_sort_creator;

    /**
     * @var string
     */
    protected $_sort_date;

    /**
     * @var bool
     */
    protected $_is_electronic;

    /**
     * @var bool
     */
    protected $_is_physical;

    /**
     * @var bool
     */
    protected $_is_digital;

    /**
     * @var array[]
     */
    protected $_links;

    /**
     * @var Holding[]
     */
    protected $_holdings;

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
     * @return string[]
     */
    public function pnx(string $category, string $field): array
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

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->_id;
    }

    public function getTitle(): string
    {
        return $this->_title;
    }

    public function getCreator(): string
    {
        return $this->_creator;
    }

    public function getContributors(): array
    {
        return $this->_contributors;
    }

    public function getDate(): string
    {
        return $this->_date;
    }

    public function getPublisher(): string
    {
        return $this->_publisher;
    }

    public function getAbstract(): string
    {
        return $this->_abstract;
    }

    public function getType(): string
    {
        return $this->_type;
    }

    public function getIsbn(): array
    {
        return $this->_isbn;
    }

    public function getIssn(): array
    {
        return $this->_issn;
    }

    public function getOclcid(): array
    {
        return $this->_oclcid;
    }

    public function getSubjects(): array
    {
        return $this->_subjects;
    }

    public function getDisplaySubject(): string
    {
        return $this->_display_subject;
    }

    public function getGenres(): array
    {
        return $this->_genres;
    }

    public function getCreatorFacet(): array
    {
        return $this->_creator_facet;
    }

    public function getCollectionFacet(): array
    {
        return $this->_collection_facet;
    }

    public function getResourcetypeFacet(): array
    {
        return $this->_resourcetype_facet;
    }

    public function getLanguages(): array
    {
        return $this->_languages;
    }

    public function getFormat(): string
    {
        return $this->_format;
    }

    public function getDescription(): array
    {
        return $this->_description;
    }

    public function getFrbrGroupId(): string
    {
        return $this->_frbr_group_id;
    }

    public function getCoverImages(): array
    {
        return $this->_cover_images;
    }

    public function getLinkToResource(): array
    {
        return $this->_link_to_resource;
    }

    public function getOpenurl(): array
    {
        return $this->_openurl;
    }

    public function getOpenurlFulltext(): array
    {
        return $this->_openurl_fulltext;
    }

    public function getSortTitle(): string
    {
        return $this->_sort_title;
    }

    public function getSortCreator(): string
    {
        return $this->_sort_creator;
    }

    public function getSortDate(): string
    {
        return $this->_sort_date;
    }

    public function isElectronic(): bool
    {
        return $this->_is_electronic;
    }

    public function isPhysical(): bool
    {
        return $this->_is_physical;
    }

    public function isDigital(): bool
    {
        return $this->_is_digital;
    }

    public function getLinks(): array
    {
        return $this->_links;
    }

    public function getHoldings(): array
    {
        return $this->_holdings;
    }

    /**
     * Read a multi-item PNX entries
     *
     * Some PNX entries have different values for different holdings. The Primo API packs these values into
     * '$$X'-delimited strings, with '$$O' containing the holding ID and '$$V' containing the asssociated
     * value, e.g.:
     *
     *     "delcategory": [
     *         "$$VAlma-P$$OALMA-BC21331257940001021",
     *         "$$VAlma-E$$OALMA-BC51460206020001021"
     *     ]
     *
     * @param $pnx_array
     * @return string[]
     */
    private function readMultiItemPNXEntry(array $pnx_array): array
    {
        $result = [];
        foreach ($pnx_array as $item) {
            preg_match('/^\$\$V(.*)\$\$O(.*)$/', $item, $matches);
            $result[$matches[2]] = $matches[1];
        }
        return $result;
    }
}