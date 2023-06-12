<?php

class ProductRepository
{

    private MySQLConnector $mysqlConnector;
    private ProductPhotoRepository $productPhotoRepository;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;
        $this->productPhotoRepository = new ProductPhotoRepository($mysqlConnector);

        $sql = "CREATE TABLE IF NOT EXISTS Product(
                    id           INT AUTO_INCREMENT PRIMARY KEY,
                    title        VARCHAR(255),
                    description  TEXT,
                    price        INT,
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
        if (!$product) {
            return null;
        }

        $product = new Product($product['id'], $product['title'], $product['description'], $product['price'], $product['zip_code'], $product['neighborhood'], $product['city'], $product['state'], $product['category_id'], $product['user_id'], new DateTime($product['created_at']));
        $product->setImages($this->productPhotoRepository->findAllByProductId($product->getId()));
        return $product;
    }

    public function findAllByUserId(int $id): array {
        $sql = "SELECT * FROM Product WHERE user_id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $productToAdd = new Product($product['id'], $product['title'], $product['description'], $product['price'], $product['zip_code'], $product['neighborhood'], $product['city'], $product['state'], $product['category_id'], $product['user_id'], new DateTime($product['created_at']));
            $productToAdd->setImages($this->productPhotoRepository->findAllByProductId($productToAdd->getId()));
            $result[] = $productToAdd;
        }

        return $result;
    }

    public function findAllByTitleContainingListOfKeywords(array $keywords, int $offset): array
    {
        $sql = "SELECT * FROM Product WHERE title LIKE ?";
        array_map(static fn($keyword) => $sql .= " OR name LIKE ?", $keywords);

        // add pagination
        $sql .= " LIMIT 10 OFFSET $offset";

        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute(array_map(static fn($keyword) => "%$keyword%", $keywords));


        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $productToAdd = new Product($product['id'], $product['title'], $product['description'], $product['price'], $product['zip_code'], $product['neighborhood'], $product['city'], $product['state'], $product['category_id'], $product['user_id'], new DateTime($product['created_at']));
            $productToAdd->setImages($this->productPhotoRepository->findAllByProductId($productToAdd->getId()));
            $result[] = $productToAdd;
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
            $productToAdd = new Product($product['id'], $product['title'], $product['description'], $product['price'], $product['zip_code'], $product['neighborhood'], $product['city'], $product['state'], $product['category_id'], $product['user_id'], new DateTime($product['created_at']));
            $productToAdd->setImages($this->productPhotoRepository->findAllByProductId($productToAdd->getId()));
            $result[] = $productToAdd;
        }

        return $result;
    }

    public function findAllMostRecent(int $offset = 0): array
    {
        $sql = "SELECT * FROM Product ORDER BY created_at DESC LIMIT 10 OFFSET $offset";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute();

        $products = $stmt->fetchAll();

        $result = [];
        foreach ($products as $product) {
            $productToAdd = new Product($product['id'], $product['title'], $product['description'], $product['price'], $product['zip_code'], $product['neighborhood'], $product['city'], $product['state'], $product['category_id'], $product['user_id'], new DateTime($product['created_at']));
            $productToAdd->setImages($this->productPhotoRepository->findAllByProductId($productToAdd->getId()));
            $result[] = $productToAdd;
        }

        return $result;
    }

    public function save(Product $product): ?Product
    {
        $id = $product->getId();
        $title = $product->getTitle();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $images = $product->getImages();
        $zipCode = $product->getZipCode();
        $neighborhood = $product->getNeighborhood();
        $city = $product->getCity();
        $state = $product->getState();
        $categoryId = $product->getCategoryId();
        $userId = $product->getUserId();


        if ($id) {
            $sql = "UPDATE Product SET title = ?, description = ?, price = ?, zip_code = ?, neighborhood = ?, city = ?, state = ?, category_id = ?, user_id = ? WHERE id = ?";
            $stmt = $this->mysqlConnector->prepare($sql);
            $stmt->execute([$title, $description, $price, $zipCode, $neighborhood, $city, $state, $categoryId, $userId, $id]);
        } else {
            $sql = "INSERT INTO Product (title, description, price, zip_code, neighborhood, city, state, category_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->mysqlConnector->prepare($sql);
            $stmt->execute([$title, $description, $price, $zipCode, $neighborhood, $city, $state, $categoryId, $userId]);
            $id = $this->mysqlConnector->lastInsertId();
        }

        if (!empty($images)) {
            foreach ($images as $image) {
                $sql = "INSERT INTO Product_photo (product_id, photo_uri) VALUES (?, ?)";
                $stmt = $this->mysqlConnector->prepare($sql);
                $stmt->execute([$id, $image]);
            }
        }

        return $this->findById($id);
    }

    public function delete(Product $product): void
    {
        $id = $product->getId();

        $sql = "DELETE FROM Product WHERE id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $sql = "DELETE FROM Product_photo WHERE product_id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);
    }
}
