<?php

namespace BCLib\PrimoClient;

/**
 * A link
 *
 * Class Link
 * @package BCLib\PrimoClient
 * @property string label
 * @property string url
 * @property string type
 *
 */
class Link
{
    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var string
     */
    protected $_type;

    public function __construct(string $label, string $url, string $type)
    {
        $this->_label = $label;
        $this->_url = $url;
        $this->_type = $type;
    }

    public function getLabel(): string
    {
        return $this->_label;
    }

    public function setLabel(string $label): void
    {
        $this->_label = $label;
    }

    public function getUrl(): string
    {
        return $this->_url;
    }

    public function setUrl(string $url): void
    {
        $this->_url = $url;
    }

    public function getType(): ?string
    {
        return $this->_type;
    }

    public function setType(string $type): void
    {
        $this->_type = $type;
    }
}
