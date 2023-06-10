<?php

class UserRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS User (
                    id INT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    cpf VARCHAR(11) NOT NULL,
                    email VARCHAR(255),
                    phone VARCHAR(15) NOT NULL,
                    password VARCHAR(512) NOT NULL
                );";
        $this->mysqlConnector->query($sql);
    }

    public function findByEmail($email): ?User
    {
        $sql = "SELECT * FROM User WHERE email = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        if ($user) {
            return new User($user['email'], $user['name'], $user['password']);
        }

        return null; // Caso o usuário não seja encontrado
    }

    public function createNewUser(User $user): bool
    {
        $email = $user->getEmail();
        $name = $user->getName();
        $password = $user->getHashedPassword();

        if ($this->findByEmail($email)) {
            return false;
        }

        $sql = "INSERT INTO User (email, name, password) VALUES (?, ?, ?)";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email, $name, $password]);

        return true;
    }

    public function authenticate($email, $password): ?User
    {
        $sql = "SELECT * FROM User WHERE email = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return new User($user['email'], $user['name'], $user['password']);
        }

        return null;
    }

    public function delete(User $user): void
    {
        $email = $user->getEmail();
        $sql = "DELETE FROM User WHERE email = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email]);
    }
}
