<?php

class UserRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS User (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    cpf VARCHAR(11) NULL,
                    email VARCHAR(255) NOT NULL,
                    phone VARCHAR(15) NULL,
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
            return new User($user['id'], $user['email'], $user['name'], $user['password'], $user['cpf'], $user['phone']);
        }

        return null; // Caso o usuário não seja encontrado
    }

    public function findById($id): ?User
    {
        $sql = "SELECT * FROM User WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $user = $stmt->fetch();
        if ($user) {
            return new User($user['id'], $user['email'], $user['name'], $user['password'], $user['cpf'], $user['phone']);
        }

        return null; // Caso o usuário não seja encontrado
    }

    public function createNewUser($email, $name, $password, $cpf, $phone): ?User
    {
        if ($this->findByEmail($email)) {
            return null;
        }

        $sql = "INSERT INTO User (email, name, password, cpf, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email, $name, $password, $cpf, $phone]);

        return $this->findByEmail($email);
    }

    public function authenticate($email, $password): ?User
    {
        $sql = "SELECT * FROM User WHERE email = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return new User($user['id'], $user['email'], $user['name'], $user['password'], $user['cpf'], $user['phone']);
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

    public function updateUser($id, $name, $password, $cpf, $phone): ?User
    {
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE User SET name = ?, password = ?, cpf = ?, phone = ? WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$name, $hashPassword, $cpf, $phone, $id]);

        return $this->findById($id);
    }
}
