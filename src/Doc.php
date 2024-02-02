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
 * @property string sort_date
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
 * @property string[] journal_title
 * @property string[] is_part_of
 * @property string[] top_level_facets
 * @property string[] source_type
 * @property bool is_peer_reviewed
 * @property bool is_online_resource
 * @property bool is_open_access
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
    protected $_description = [];

    /**
     * @var string
     */
    protected $_frbr_group_id;

    /**
     * @var string[]
     */
    protected $_cover_images = [];

    /**
     * @var Link[]
     */
    protected $_link_to_resource = [];

    /**
     * @var Link[]
     */
    protected $_openurl = [];

    /**
     * @var Link[]
     */
    protected $_openurl_fulltext = [];

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
    protected $_is_electronic = false;

    /**
     * @var bool
     */
    protected $_is_physical = false;

    /**
     * @var bool
     */
    protected $_is_digital = false;

    /**
     * @var array[]
     */
    protected $_links = [];

    /**
     * @var Holding[]
     */
    protected $_holdings = [];

    /**
     * @var string[]
     */
    protected $_is_part_of = [];

    /**
     * @var string[]
     */
    protected $_journal_title = [];

    /**
     * @var bool
     */
    protected $_available;

    /**
     * @var string[]
     */
    protected array $_top_level_facets = [];

    protected bool $_is_peer_reviewed = false;

    protected bool $_is_online_resource = false;

    protected bool $_is_open_access = false;

    /**
     * @var string[]
     */
    protected array $_source_type = [];

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

    public function getId(): ?string
    {
        return $this->_id;
    }

    public function getTitle(): ?string
    {
        return $this->_title;
    }

    public function getCreator(): ?string
    {
        return $this->_creator;
    }

    /**
     * @return string[]
     */
    public function getContributors(): array
    {
        return $this->_contributors;
    }

    public function getDate(): ?string
    {
        return $this->_date;
    }

    public function getPublisher(): ?string
    {
        return $this->_publisher;
    }

    public function getAbstract(): ?string
    {
        return $this->_abstract;
    }

    public function getType(): ?string
    {
        return $this->_type;
    }

    /**
     * @return string[]
     */
    public function getIsbn(): array
    {
        return $this->_isbn;
    }

    /**
     * @return string[]
     */
    public function getIssn(): array
    {
        return $this->_issn;
    }

    /**
     * @return string[]
     */
    public function getOclcid(): array
    {
        return $this->_oclcid;
    }

    /**
     * @return string[]
     */
    public function getSubjects(): array
    {
        return $this->_subjects;
    }

    public function getDisplaySubject(): ?string
    {
        return $this->_display_subject;
    }

    /**
     * @return string[]
     */
    public function getGenres(): array
    {
        return $this->_genres;
    }

    /**
     * @return string[]
     */
    public function getCreatorFacet(): array
    {
        return $this->_creator_facet;
    }

    /**
     * @return string[]
     */
    public function getCollectionFacet(): array
    {
        return $this->_collection_facet;
    }

    /**
     * @return string[]
     */
    public function getResourcetypeFacet(): array
    {
        return $this->_resourcetype_facet;
    }

    /**
     * @return string[]
     */
    public function getLanguages(): array
    {
        return $this->_languages;
    }

    public function getFormat(): ?string
    {
        return $this->_format;
    }

    /**
     * @return string[]
     */
    public function getDescription(): array
    {
        return $this->_description;
    }

    public function getFrbrGroupId(): ?string
    {
        return $this->_frbr_group_id;
    }

    /**
     * @return Link[]
     */
    public function getCoverImages(): array
    {
        return $this->_cover_images;
    }

    /**
     * @return Link[]
     */
    public function getLinkToResource(): array
    {
        return $this->_link_to_resource;
    }

    /**
     * @return Link[]
     */
    public function getOpenurl(): array
    {
        return $this->_openurl;
    }

    /**
     * @return Link[]
     */
    public function getOpenurlFulltext(): array
    {
        return $this->_openurl_fulltext;
    }

    public function getSortTitle(): ?string
    {
        return $this->_sort_title;
    }

    public function getSortCreator(): ?string
    {
        return $this->_sort_creator;
    }

    public function getSortDate(): ?string
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

    /**
     * @return Link[]
     */
    public function getLinks(): array
    {
        return $this->_links;
    }

    /**
     * @return Holding[]
     */
    public function getHoldings(): array
    {
        return $this->_holdings;
    }

    /**
     * @return string[]
     */
    public function getTopLevelFacets(): array
    {
        return $this->_top_level_facets;
    }

    public function isPeerReviewed(): bool
    {
        return in_array('peer_reviewed', $this->getTopLevelFacets());
    }

    public function isOnlineResource(): bool
    {
        return in_array('online_resources', $this->getTopLevelFacets());
    }

    public function getSourceType(): array
    {
        return $this->_source_type;
    }

    public function isOpenAccess(): bool
    {
        return $this->_is_open_access;
    }

    public function setId(?string $id): void
    {
        $this->_id = $id;
    }

    public function setTitle(?string $title): void
    {
        $this->_title = $title;
    }

    public function setCreator(?string $creator): void
    {
        $this->_creator = $creator;
    }

    public function setContributors(?array $contributors): void
    {
        $this->_contributors = $contributors;
    }

    public function setDate(?string $date): void
    {
        $this->_date = $date;
    }

    public function setPublisher(?string $publisher): void
    {
        $this->_publisher = $publisher;
    }

    public function setAbstract(?string $abstract): void
    {
        $this->_abstract = $abstract;
    }

    public function setType(?string $type): void
    {
        $this->_type = $type;
    }

    public function setIsbn(?array $isbn): void
    {
        $this->_isbn = $isbn;
    }

    public function setIssn(?array $issn): void
    {
        $this->_issn = $issn;
    }

    public function setOclcid(?array $oclcid): void
    {
        $this->_oclcid = $oclcid;
    }

    public function setSubjects(?array $subjects): void
    {
        $this->_subjects = $subjects;
    }

    public function setDisplaySubject(?string $display_subject): void
    {
        $this->_display_subject = $display_subject;
    }

    public function setGenres(?array $genres): void
    {
        $this->_genres = $genres;
    }

    public function setCreatorFacet(?array $creator_facet): void
    {
        $this->_creator_facet = $creator_facet;
    }

    public function setCollectionFacet(?array $collection_facet): void
    {
        $this->_collection_facet = $collection_facet;
    }

    public function setResourcetypeFacet(?array $resourcetype_facet): void
    {
        $this->_resourcetype_facet = $resourcetype_facet;
    }

    public function setLanguages(?array $languages): void
    {
        $this->_languages = $languages;
    }

    public function setFormat(?string $format): void
    {
        $this->_format = $format;
    }

    public function setDescription(?array $description): void
    {
        $this->_description = $description;
    }

    public function setFrbrGroupId(?string $frbr_group_id): void
    {
        $this->_frbr_group_id = $frbr_group_id;
    }

    public function setCoverImages(?array $cover_images): void
    {
        $this->_cover_images = $cover_images;
    }

    public function setLinkToResource(?array $link_to_resource): void
    {
        $this->_link_to_resource = $link_to_resource;
    }

    public function setOpenurl(?array $openurl): void
    {
        $this->_openurl = $openurl;
    }

    public function setOpenurlFulltext(?array $openurl_fulltext): void
    {
        $this->_openurl_fulltext = $openurl_fulltext;
    }

    public function setSortTitle(?string $sort_title): void
    {
        $this->_sort_title = $sort_title;
    }

    public function setSortCreator(?string $sort_creator): void
    {
        $this->_sort_creator = $sort_creator;
    }

    public function setSortDate(?string $sort_date): void
    {
        $this->_sort_date = $sort_date;
    }

    public function setIsElectronic(?bool $is_electronic): void
    {
        $this->_is_electronic = $is_electronic;
    }

    public function setIsPhysical(?bool $is_physical): void
    {
        $this->_is_physical = $is_physical;
    }

    public function setIsDigital(?bool $is_digital): void
    {
        $this->_is_digital = $is_digital;
    }

    public function setLinks(?array $links): void
    {
        $this->_links = $links;
    }

    public function setHoldings(?array $holdings): void
    {
        foreach ($holdings as $holding) {
            if (!is_a($holding, Holding::class)) {
                $type = is_object($holding) ? get_class($holding) : gettype($holding);
                throw new InvalidArgumentException("Can't add $type to Holding list");
            }
        }
        $this->_holdings = $holdings;
    }

    /**
     * @return string[]
     */
    public function getIsPartOf(): array
    {
        return $this->_is_part_of;
    }

    public function setIsPartOf(array $is_part_of): void
    {
        $this->_is_part_of = $is_part_of;
    }

    /**
     * @return string[]
     */
    public function getJournalTitle(): array
    {
        return $this->_journal_title;
    }

    public function setJournalTitle(array $journal_title): void
    {
        $this->_journal_title = $journal_title;
    }

    public function isAvailable(): ?bool
    {
        return $this->_available;
    }

    public function setAvailable(bool $availble): void
    {
        $this->_available = $availble;
    }

    public function setTopLevelFacets(array $top_level_facets): void
    {
        $this->_top_level_facets = $top_level_facets;
        $this->_is_peer_reviewed = in_array('peer_reviewed', $top_level_facets);
        $this->_is_online_resource = in_array('online_resources', $top_level_facets);
    }

    public function setSourceType(array $source_type): void
    {
        $this->_source_type = $source_type;
    }

    public function setIsOpenAccess(bool $is_open_access):void {
        $this->_is_open_access = $is_open_access;
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
