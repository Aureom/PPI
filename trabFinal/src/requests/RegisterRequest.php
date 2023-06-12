<?php

require_once __DIR__ . "/../interfaces/errors/BadRequest.php";
require_once __DIR__ . "/../security/Auth.php";
require_once __DIR__ . "/../model/User.php";
require_once __DIR__ . "/../service/UserService.php";

class RegisterRequest
{
    private UserService $userService;

    private string $name;
    private string $email;
    private string $cpf;
    private string $phone;
    private string $password;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->name = $_POST['name'] ?? null;
        $this->email = $_POST['email'] ?? null;
        $this->cpf = $_POST['cpf'] ?? null;
        $this->phone = $_POST['phone'] ?? null;
        $this->password = $_POST['password'] ?? null;
    }

    public function validateFieldsAndSave(): void
    {
        if(!$this->validateFields()) {
            return;
        }

        echo "chegou aqui";
        $user = Auth::register($this->email, $this->name, $this->password, $this->cpf, $this->phone);

        if ($user) {
            header('Location: ../pages/home.php');
            exit;
        }

        $response = new BadRequest("Ocorreu um erro ao realizar o registro.");
        echo $response->toJson();
    }

    private function validateFields(): bool
    {
        if (empty($this->email) || empty($this->cpf) || empty($this->phone) || empty($this->name) || empty($this->password)) {
            $badRequest = new BadRequest("Todos os campos são obrigatórios");
            echo $badRequest->toJson();
            return false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $badRequest = new BadRequest("O email informado é inválido");
            echo $badRequest->toJson();
            return false;
        }

        if (strlen($this->cpf) != 11) {
            $badRequest = new BadRequest("O CPF informado é inválido");
            echo $badRequest->toJson();
            return false;
        }

        if (strlen($this->phone) != 11) {
            $badRequest = new BadRequest("O telefone informado é inválido");
            echo $badRequest->toJson();
            return false;
        }

        if (strlen($this->password) < 8) {
            $badRequest = new BadRequest("A senha deve conter no mínimo 8 caracteres");
            echo $badRequest->toJson();
            return false;
        }

        if (strlen($this->name) < 3) {
            $badRequest = new BadRequest("O nome deve conter no mínimo 3 caracteres");
            echo $badRequest->toJson();
            return false;
        }

        if ($this->userService->findByEmail($this->email)) {
            $badRequest = new BadRequest("O email informado já está em uso");
            echo $badRequest->toJson();
            return false;
        }

        return true;
    }

}