<?php

namespace BCLib\PrimoClient;

/**
 * A Holding as returned by the Brief Search API
 *
 * Class Holding
 * @package BCLib\PrimoClient
 */
class Holding
{
    /**
     * @var string
     */
    public $ils_id;

    /**
     * @var string
     */
    public $library_code;

    /**
     * @var string
     */
    public $location_code;

    /**
     * @var string
     */
    public $location_display;

    /**
     * @var string
     */
    public $call_number;

    /**
     * @var string
     */
    public $availability_status;

    public function __construct(
        string $ils_id,
        string $library_code,
        string $location_code,
        string $location_display,
        string $call_number,
        string $availability_status
    ) {
        $this->ils_id = $ils_id;
        $this->library_code = $library_code;
        $this->location_code = $location_code;
        $this->location_display = $location_display;
        $this->call_number = $call_number;
        $this->availability_status = $availability_status;
    }
}