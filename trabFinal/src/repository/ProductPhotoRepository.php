<?php

class ProductPhotoRepository
{

    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Product_photo (
                    product_id INT,
                    photo_uri VARCHAR(512),
                    FOREIGN KEY (product_id) REFERENCES Product(id)
                );";
        $this->mysqlConnector->query($sql);
    }

    public function findAllByProductId(int $id): array {
        $sql = "SELECT * FROM Product_photo WHERE product_id = ?";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$id]);

        $photos = $stmt->fetchAll();

        $result = [];
        foreach ($photos as $photo) {
            $result[] = new ProductPhoto($photo['product_id'], $photo['photo_uri']);
        }

        return $result;
    }

    public function save(ProductPhoto $photo): void
    {
        $sql = "INSERT INTO Product_photo (product_id, photo_uri) VALUES (?, ?)";
        $stmt = $this->mysqlConnector->prepare($sql);
        $stmt->execute([$photo->getProductId(), $photo->getPhotoUri()]);
    }
}
