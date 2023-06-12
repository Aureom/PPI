<?php

class CategoryRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Category (
                    id INT PRIMARY KEY,
                    name VARCHAR(255),
                    description TEXT
                );";
        $this->mysqlConnector->query($sql);
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM Category;";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(static function ($category) {
            return new Category(
                $category["id"],
                $category["name"],
                $category["description"]
            );
        }, $categories);
    }

    public function create(Category $category): void
    {
        $sql = "INSERT INTO Category (id, name, description) VALUES (?, ?, ?);";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([
            $category->getId(),
            $category->getName(),
            $category->getDescription()
        ]);
    }
}
