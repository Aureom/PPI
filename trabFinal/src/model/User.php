<?php
class User {
    protected int $id;
    protected string $email;
    protected string $name;
    protected string $password;
    protected ?string $cpf;
    protected ?string $phone;

    public function __construct($id, $email, $name, $password, $cpf = null, $phone = null) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->cpf = $cpf;
        $this->phone = $phone;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getHashedPassword(): string {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }

    public function getCpf(): string {
        return $this->cpf;
    }

    public function getPhone(): string {
        return $this->phone;
    }

}
