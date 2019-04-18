<?php

namespace BCLib\PrimoClient;

/**
 * A value in a Brief Search response facet
 *
 * Class ResponseFacetValue
 * @package BCLib\PrimoClient
 */
class ResponseFacetValue
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $count;

    public function __construct(string $value, string $count)
    {
        $this->value = $value;
        $this->count = $count;
    }
}