<?php

namespace BCLib\PrimoClient;

/**
 * A Holding as returned by the Brief Search API
 *
 * Class Holding
 * @package BCLib\PrimoClient
 *
 * @property string ils_id
 * @property string library_code
 * @property string location_code
 * @property string location_display
 * @property string call_number
 * @property string availability_status
 */
class Holding
{
    use GetterSetter;

    /**
     * @var string
     */
    private $_ils_id;

    /**
     * @var string
     */
    private $_library_code;

    /**
     * @var string
     */
    private $_location_code;

    /**
     * @var string
     */
    private $_location_display;

    /**
     * @var string
     */
    private $_call_number;

    /**
     * @var string
     */
    private $_availability_status;

    public function __construct(
        string $ils_id,
        string $library_code,
        string $location_code,
        string $location_display,
        string $call_number,
        string $availability_status
    ) {
        $this->_ils_id = $ils_id;
        $this->_library_code = $library_code;
        $this->_location_code = $location_code;
        $this->_location_display = $location_display;
        $this->_call_number = $call_number;
        $this->_availability_status = $availability_status;
    }

    public function getIlsId(): string
    {
        return $this->_ils_id;
    }

    public function setIlsId(string $ils_id): void
    {
        $this->_ils_id = $ils_id;
    }

    public function getLibraryCode(): string
    {
        return $this->_library_code;
    }

    public function setLibraryCode(string $library_code): void
    {
        $this->_library_code = $library_code;
    }

    public function getLocationCode(): string
    {
        return $this->_location_code;
    }

    public function setLocationCode(string $location_code): void
    {
        $this->_location_code = $location_code;
    }

    public function getLocationDisplay(): string
    {
        return $this->_location_display;
    }

    public function setLocationDisplay(string $location_display): void
    {
        $this->_location_display = $location_display;
    }

    public function getCallNumber(): string
    {
        return $this->_call_number;
    }

    public function setCallNumber(string $call_number): void
    {
        $this->_call_number = $call_number;
    }

    public function getAvailabilityStatus(): string
    {
        return $this->_availability_status;
    }

    public function setAvailabilityStatus(string $availability_status): void
    {
        $this->_availability_status = $availability_status;
    }
}