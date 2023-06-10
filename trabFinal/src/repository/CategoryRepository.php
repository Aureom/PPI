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

}
