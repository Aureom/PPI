<?php
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../service/UserService.php";
require_once __DIR__ . "/../security/Auth.php";
include_once __DIR__ . "/../interfaces/errors/BadRequest.php";

$mysqlConnector = new MySQLConnector();
$userRepository = new UserRepository($mysqlConnector);
$userService = new UserService($userRepository);

if (Auth::user()) {
    header('Location: ../pages/home.php');
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = Auth::attempt($email, $password);

    if ($user) {
        header('Location: ../pages/home.php');
        exit;
    }

    // Login falhou, exibe uma mensagem de erro
    header('Content-type: application/json');

    $response = new BadRequest("Usuário ou senha inválidos");

    try {
        echo $response->toJson();
    } catch (JsonException $e) {
        echo $e->getMessage();
    }

    exit;
}
?>