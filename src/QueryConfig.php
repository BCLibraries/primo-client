<?php

namespace BCLib\PrimoClient;


class QueryConfig
{
    /**
     * @var string
     */
    public $apikey;

    /**
     * @var string
     */
    public $tab;

    /**
     * @var string
     */
    public $vid;

    /**
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $inst;

    public function __construct(string $apikey, string $tab, string $vid, string $scope, string $inst = null)
    {
        $this->tab = $tab;
        $this->vid = $vid;
        $this->scope = $scope;
        $this->apikey = $apikey;
        $this->inst = $inst;
    }
}