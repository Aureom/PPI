<?php

class ProductRepository
{

    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Product(
                    id           INT PRIMARY KEY,
                    title        VARCHAR(255),
                    description  TEXT,
                    price        DECIMAL(10, 2),
                    zip_code     VARCHAR(9),
                    neighborhood VARCHAR(255),
                    city         VARCHAR(255),
                    state        VARCHAR(2),
                    category_id  INT,
                    user_id      INT,
                    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (category_id) REFERENCES Category (id),
                    FOREIGN KEY (user_id) REFERENCES User (id)
                );";
        $this->mysqlConnector->query($sql);
    }

    public function findById($id): ?Product
    {
        $sql = "SELECT * FROM Product WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $product = $stmt->fetch();

        if ($product) {
            return new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['image']);
        }

        return null; // Caso o produto não seja encontrado
    }

    public function findAllByUserId(int $id): array {
        $sql = "SELECT * FROM Product WHERE user_id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $result[] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['image']);
        }

        return $result;
    }

    public function findAllByNameContainingListOfKeywords(array $keywords, int $offset): array
    {
        $result = [];
        $sql = "SELECT * FROM Product WHERE name LIKE ?";
        array_map(static fn($keyword) => $sql .= " OR name LIKE ?", $keywords);

        // add pagination
        $sql .= " LIMIT 10 OFFSET $offset";

        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute(array_map(static fn($keyword) => "%$keyword%", $keywords));


        $products = $stmt->fetchAll();

        foreach ($products as $product) {
            $result[] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['image']);
        }

        return $result;
    }

    public function findAllByDescriptionIncluding($description): array
    {
        $sql = "SELECT * FROM Product WHERE description LIKE ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute(["%$description%"]);

        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $result[] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['image']);
        }

        return $result;
    }

    public function findAllMostRecent(): array
    {
        $sql = "SELECT * FROM Product ORDER BY created_at DESC LIMIT 10";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute();

        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $result[] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['image']);
        }

        return $result;
    }

    public function save(Product $product)
    {
        $id = $product->getId();
        $name = $product->getName();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $image = $product->getImageUrl();

        if ($id) {
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
            $stmt = $this->mysqlConnector->prepare($sql);
            $stmt->execute([$name, $description, $price, $image, $id]);
        } else {
            $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
            $stmt = $this->mysqlConnector->prepare($sql);
            $stmt->execute([$name, $description, $price, $image]);
        }
    }

    public function delete(Product $product)
    {
        $id = $product->getId();

        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);
    }
}
