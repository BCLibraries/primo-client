<?php

namespace BCLib\PrimoClient;

/**
 * A link
 *
 * Class Link
 * @package BCLib\PrimoClient
 */
class Link
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $type;

    public function __construct(string $label, string $url, string $type)
    {
        $this->label = $label;
        $this->url = $url;
        $this->type = $type;
    }
}