<?php

namespace BCLib\PrimoClient;

/**
 * A facet in a Brief Search response
 *
 * Class ResponseFacet
 * @package BCLib\PrimoClient
 *
 * @property string name
 * @property ResponseFacetValue[] values
 */
class ResponseFacet
{
    use GetterSetter;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var ResponseFacetValue[]
     */
    private $_values = [];

    public function __construct(string $name)
    {
        $this->_name = $name;
    }

    /**
     * Sorts values in place by frequency
     */
    public function sortByFrequency(): void
    {
        usort(
            $this->_values,
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
            $this->_values,
            function ($a, $b) {
                return strcasecmp($a->value, $b->value);
            }
        );
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return ResponseFacetValue[]
     */
    public function getValues(): array
    {
        return $this->_values;
    }

    /**
     * @param ResponseFacetValue[] $values
     */
    public function setValues(array $values): void
    {
        $this->_values = $values;
    }
}