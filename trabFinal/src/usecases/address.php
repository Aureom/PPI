<?php
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../repository/AddressRepository.php";
require_once __DIR__ . "/../service/AddressService.php";

$mysqlConnector = new MySQLConnector();
$addressRepository = new AddressRepository($mysqlConnector);
$addressService = new AddressService($addressRepository);

header('Content-type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zipCode = $_POST['zip_code'] ?? null;
    if (!$zipCode) {
        http_response_code(400);
        echo json_encode(['error' => 'CEP não informado']);
        exit;
    }

    $address = $addressService->findByZipCode($zipCode);

    if ($address) {
        http_response_code(200);
        echo json_encode($address);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'CEP não encontrado']);
    }
    exit;
}
?>