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

$path = $_SERVER['PATH_INFO'] ?? '';
$request = $_SERVER['REQUEST_METHOD'] ?? '';

if ($request === 'POST' && $path === '/update') {
    if (!Auth::check()) {
        http_response_code(401);
        exit;
    }

    $name = $_POST['name'] ?? null;
    $password = $_POST['password'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $phone = $_POST['phone'] ?? null;

    $user = $userService->update(Auth::user()->getId(), $name, $password, $cpf, $phone);
}