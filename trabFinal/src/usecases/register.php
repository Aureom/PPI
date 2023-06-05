<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/repository/MySQLConnector.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/model/User.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/repository/UserRepository.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/service/UserService.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/interfaces/errors/BadRequest.php');
session_start();

// Cria uma instância do MySQLConnector
$mysqlConnector = new MySQLConnector();

// Cria uma instância do UserRepository
$userRepository = new UserRepository($mysqlConnector);

// Cria uma instância do UserService
$userService = new UserService($userRepository);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'] ?? '';
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    print_r($_POST);

    $user = $userService->register($email, $name, $password);    
    
    if($user) {
        $_SESSION['user'] = $user;
        
        header('Location: home.php');
        exit;
    } else {
        header('Content-type: application/json');
        $response = new BadRequest("Ocorreu um erro ao realizar o registro.");

        echo json_encode($response);
        exit;
    }
}