<?php
require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../repository/UserRepository.php";
require_once __DIR__ . "/../service/UserService.php";
include_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../security/Auth.php";

// Cria uma instância do MySQLConnector
$mysqlConnector = new MySQLConnector();
$userRepository = new UserRepository($mysqlConnector);
$userService = new UserService($userRepository);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = Auth::register($email, $name, $password);
    
    if($user) {
        header('Location: ../pages/home.php');
        exit;
    }

    header('Content-type: application/json');
    $response = new BadRequest("Ocorreu um erro ao realizar o registro.");

    try {
        echo json_encode($response, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo $e->getMessage();
        $response = new BadRequest("Ocorreu um erro ao realizar o registro.");
    }

    exit;
}