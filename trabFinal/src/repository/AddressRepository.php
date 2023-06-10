<?php

class AddressRepository
{
    private MySQLConnector $mysqlConnector;

    public function __construct(MySQLConnector $mysqlConnector)
    {
        $this->mysqlConnector = $mysqlConnector;

        $sql = "CREATE TABLE IF NOT EXISTS Address (
                    zip_code VARCHAR(9),
                    neighborhood VARCHAR(255),
                    city VARCHAR(255),
                    state VARCHAR(2)
                );";
        $this->mysqlConnector->query($sql);
    }

}
