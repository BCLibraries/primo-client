<?php

namespace BCLib\PrimoClient;

class Config
{
    /**
     * @var string
     */
    private $apikey;

    /**
     * @var string
     */
    private $gateway;

    /**
     * @var string
     */
    private $vid;

    /**
     * @var string
     */
    private $tab;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $inst;

    public function __construct(
        string $apikey,
        string $gateway,
        string $vid,
        string $tab,
        string $scope,
        string $inst = null
    ) {
        $this->apikey = $apikey;
        $this->gateway = $gateway;
        $this->vid = $vid;
        $this->tab = $tab;
        $this->scope = $scope;
        $this->inst = $inst;
    }
}