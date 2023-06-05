<?php

include($_SERVER['DOCUMENT_ROOT'].'/trabFinal/src/utils/print.php');

class MySQLConnector {
    private $host;
    private $username;
    private $password;
    private $database;
    private $connection;
    private $connectionQueue;

    public function __construct(){
        $this->host = "sql10.freemysqlhosting.net";
        $this->username = "sql10622545";
        $this->password = "TGUxTJQG5d";
        $this->database = "sql10622545";
        $this->connectionQueue = new SplQueue();
    }

    public function connect() {
        $dsn = "mysql:host={$this->host};dbname={$this->database};";
        
        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, array(PDO::ATTR_TIMEOUT => 5));

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {            
            $this->log("Connection error: ".$e->getMessage());
            
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

    public function releaseConnection($connection) {
        $this->connectionQueue->enqueue($connection);
    }

    public function query($sql) {
        $connection = $this->getConnection();

        try {
            $stmt = $connection->query($sql);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }

        $this->releaseConnection($connection);

        return $stmt;
    }

    function log($message)
    {
        $message = date("H:i:s") . " - $message - ".PHP_EOL;
        print($message);
        flush();
        ob_flush();
    }
}
