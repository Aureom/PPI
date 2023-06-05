<?php
class User {
    private $email;
    private $name;
    private $password;

    public function __construct($email, $name, $password) {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    // Getters e setters para os atributos
    public function getEmail() {
        return $this->email;
    }

    public function getName() {
        return $this->name;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getHashedPassword() {
        return password_hash($this->password, "argon2id");
    }
}
