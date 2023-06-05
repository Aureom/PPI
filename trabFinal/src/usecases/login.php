<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/repository/MySQLConnector.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/model/User.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/repository/UserRepository.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/service/UserService.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/utils/print.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/interfaces/errors/BadRequest.php');

$mysqlConnector = new MySQLConnector();
$userRepository = new UserRepository($mysqlConnector);
$userService = new UserService($userRepository);

session_start();

if (isset($_SESSION['user'])) {
    header('Location: ../pages/home.php');
    exit;
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userAuthenticated = $userService->login($email, $password);

    if ($userAuthenticated) {
        // Login bem-sucedido, define a variável de sessão
        $_SESSION['user'] = serialize($userAuthenticated);

        header('Location: ../pages/home.php');
        exit;
    } else {
        // Login falhou, exibe uma mensagem de erro
        header('Content-type: application/json');

        $response = new BadRequest("Usuário ou senha inválidos");

        echo json_encode($response);
        exit;
    }
}
?>