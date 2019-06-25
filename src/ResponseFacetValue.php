<?php

namespace BCLib\PrimoClient;

/**
 * A value in a Brief Search response facet
 *
 * Class ResponseFacetValue
 * @package BCLib\PrimoClient
 *
 * @property string value
 * @property int count
 */
class ResponseFacetValue
{
    use GetterSetter;

    /**
     * @var string
     */
    private $_value;

    /**
     * @var string
     */
    private $_count;

    public function __construct(string $value, string $count)
    {
        $this->_value = $value;
        $this->_count = $count;
    }

    public function getValue(): string
    {
        return $this->_value;
    }

    public function setValue(string $value): void
    {
        $this->_value = $value;
    }

    public function getCount(): int
    {
        return $this->_count;
    }

    public function setCount(int $count): void
    {
        $this->_count = $count;
    }
}