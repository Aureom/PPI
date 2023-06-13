<?php

require_once __DIR__ . "/../interfaces/errors/UnauthorizedRequest.php";
require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../repository/AddressRepository.php";
require_once __DIR__ . "/../model/Address.php";

class AddressService
{
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    public function findByZipCode($zipCode): ?Address
    {
        return $this->addressRepository->findByZipCode($zipCode);
    }
}