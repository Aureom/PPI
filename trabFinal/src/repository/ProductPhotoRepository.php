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
}
