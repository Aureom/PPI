<?php

class Address implements JsonSerializable
{
    protected string $zipCode;
    protected string $neighborhood;
    protected string $city;
    protected string $state;

    public function __construct(string $zipCode, string $neighborhood, string $city, string $state)
    {
        $this->zipCode = $zipCode;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;

    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function jsonSerialize(): array
    {
        return [
            "zipCode" => $this->zipCode,
            "neighborhood" => $this->neighborhood,
            "city" => $this->city,
            "state" => $this->state
        ];
    }
}