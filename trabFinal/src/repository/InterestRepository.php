<?php

require_once __DIR__ . "/../database/MySQLConnector.php";
require_once __DIR__ . "/../model/Interest.php";

class InterestRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Interest (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    message TEXT,
                    date_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    contact VARCHAR(255),
                    product_id INT,
                    FOREIGN KEY (product_id) REFERENCES Product(id)
                );";
        $this->mysqlConnector->query($sql);
    }

    public function findById(int $id): ?Interest {
        $sql = "SELECT * FROM Interest WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }

        return new Interest($row['id'], $row['message'], new DateTime($row['date_time']), $row['contact'], $row['product_id']);
    }

    public function findAllByProductId(int $productId): array
    {
        $sql = "SELECT * FROM Interest WHERE product_id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$productId]);

        $interests = [];
        while ($row = $stmt->fetch()) {
            $interests[] = new Interest($row['id'], $row['message'], $row['date_time'], $row['contact'], $row['product_id']);
        }

        return $interests;
    }

    public function save(int $productId, string $message, mixed $contact): ?Interest
    {
        $sql = "INSERT INTO Interest (message, contact, product_id) VALUES (?, ?, ?)";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$message, $contact, $productId]);

        return $this->findById($this->mysqlConnector->lastInsertId());
    }
}
