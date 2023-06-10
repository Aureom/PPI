<?php

class InterestRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Interest (
                    id INT PRIMARY KEY,
                    message TEXT,
                    date_time DATETIME,
                    contact VARCHAR(255),
                    product_id INT,
                    FOREIGN KEY (product_id) REFERENCES Product(id)
                );";
        $this->mysqlConnector->query($sql);
    }
}
