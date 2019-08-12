<?php

namespace BCLib\PrimoClient;

class Item
{
    /**
     * @var string
     */
    protected $holding_id;

    /**
     * @var string
     */
    protected $institution;

    /**
     * @var string
     */
    protected $library;

    /**
     * @var string
     */
    protected $library_display;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $call_number;

    /**
     * @var string
     */
    protected $availability;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $number_unavailable;

    /**
     * @var string
     */
    protected $location_code;

    /**
     * @var string
     */
    protected $multi_volume;

    /**
     * @var string
     */
    protected $number_loans;

    public function getHoldingId(): ?string
    {
        return $this->holding_id;
    }

    public function setHoldingId(string $holding_id): void
    {
        $this->holding_id = $holding_id;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): void
    {
        $this->institution = $institution;
    }

    public function getLibrary(): ?string
    {
        return $this->library;
    }

    public function setLibrary(string $library): void
    {
        $this->library = $library;
    }

    public function getLibraryDisplay(): ?string
    {
        return $this->library_display;
    }

    public function setLibraryDisplay(string $library_display): void
    {
        $this->library_display = $library_display;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getCallNumber(): ?string
    {
        return $this->call_number;
    }

    public function setCallNumber(string $call_number): void
    {
        $this->call_number = $call_number;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(string $availability): void
    {
        $this->availability = $availability;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getNumberUnavailable(): ?string
    {
        return $this->number_unavailable;
    }

    public function setNumberUnavailable(string $number_unavailable): void
    {
        $this->number_unavailable = $number_unavailable;
    }

    public function getLocationCode(): ?string
    {
        return $this->location_code;
    }

    public function setLocationCode(string $location_code): void
    {
        $this->location_code = $location_code;
    }

    public function getMultiVolume(): ?string
    {
        return $this->multi_volume;
    }

    public function setMultiVolume(string $multi_volume): void
    {
        $this->multi_volume = $multi_volume;
    }

    public function getNumberLoans(): ?string
    {
        return $this->number_loans;
    }

    public function setNumberLoans(string $number_loans): void
    {
        $this->number_loans = $number_loans;
    }
}