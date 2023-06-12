<?php
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../service/UserService.php";
include_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../security/Auth.php";
require_once __DIR__ . "/../requests/RegisterRequest.php";

$mysqlConnector = new MySQLConnector();
$userRepository = new UserRepository($mysqlConnector);
$userService = new UserService($userRepository);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cria uma instância do RegisterRequest
    $request = new RegisterRequest($userService);
    $request->validateFieldsAndSave();
}