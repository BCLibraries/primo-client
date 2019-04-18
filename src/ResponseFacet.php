<?php

namespace BCLib\PrimoClient;

/**
 * A facet in a Brief Search response
 *
 * Class ResponseFacet
 * @package BCLib\PrimoClient
 */
class ResponseFacet
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var ResponseFacetValue[]
     */
    public $values = [];

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->values = [];
    }

    /**
     * Sorts values in place by frequency
     */
    public function sortByFrequency(): void
    {
        usort(
            $this->values,
            function ($a, $b) {
                return $b->count - $a->count;
            }
        );
    }

    /**
     * Sorts values in place alphabetically
     */
    public function sortAlphabetically(): void
    {
        usort(
            $this->values,
            function ($a, $b) {
                return strcasecmp($a->value, $b->value);
            }
        );
    }
}