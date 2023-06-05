<?php

// UserRepository.php
class UserRepository {
    private $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector) {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS users (
            email VARCHAR(64) PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            password VARCHAR(512) NOT NULL
        )";
        $this->mysqlConnector->query($sql);
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->mysqlConnector->query($sql);

        // Transformar o resultado em um objeto UserModel
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            return new User($row['email'], $row['name'], $row['password']);
        }

        return null; // Caso o usuário não seja encontrado
    }

    public function save(User $user) {
        $email = $user->getEmail();
        $name = $user->getName();
        $password = $user->getHashedPassword();

        $sql = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$password')";

        $this->mysqlConnector->query($sql);

        return true;
    }

    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->mysqlConnector->query($sql);

        $user = $result->fetch();
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    public function delete(User $user) {
        $email = $user->getEmail();
        $sql = "DELETE FROM users WHERE email = '$email'";
        $this->mysqlConnector->query($sql);
    }
}
