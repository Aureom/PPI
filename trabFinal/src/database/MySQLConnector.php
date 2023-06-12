<?php

class MySQLConnector {
    private string $host;
    private string $username;
    private string $password;
    private string $database;
    private PDO $connection;
    private SplQueue $connectionQueue;

    public function __construct(){
        $this->host = "sql10.freemysqlhosting.net";
        $this->username = "sql10625130";
        $this->password = "LsDN4lw9ev";
        $this->database = "sql10625130";
        $this->connectionQueue = new SplQueue();
    }

    public function connect(): void {
        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connectionQueue->enqueue($this->connection);
        } catch (PDOException $e) {            
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        if ($this->connectionQueue->isEmpty()) {
            $this->connect();
        } else {
            $this->connection = $this->connectionQueue->dequeue();
        }

        return $this->connection;
    }

    public function releaseConnection($connection): void {
        $this->connectionQueue->enqueue($connection);
    }

    public function query(string $sql) {
        $connection = $this->getConnection();

        try {
            $stmt = $connection->query($sql);
            $this->releaseConnection($connection);
            return $stmt;
        } catch (PDOException $e) {
            $this->releaseConnection($connection);
            die("Query failed: " . $e->getMessage());
        }
    }

    public function prepare(string $sql): PDOStatement {
        $connection = $this->getConnection();

        try {
            $stmt = $connection->prepare($sql);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }

        return $stmt;
    }

    public function lastInsertId(): int {
        return $this->connection->lastInsertId();
    }
}
