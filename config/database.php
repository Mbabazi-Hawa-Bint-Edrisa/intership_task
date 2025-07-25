<?php
class Database {
    private $host = "localhost";
    private $db_name = "registration_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        try {
            //  Connecting to MySQL server without database
            $tempConn = new PDO("mysql:host=".$this->host, $this->username, $this->password);
            $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // creating database 
            $tempConn->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name}");

            // Connecting to the databse 
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, 
                                  $this->username, 
                                  $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Creating users table 
            $this->createUsersTable();

        } catch(PDOException $e) {
            echo "Database connection error: " . $e->getMessage();
        }

        return $this->conn;
    }

    private function createUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            gender VARCHAR(10),
            country VARCHAR(50),
            bio TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->conn->exec($sql);
    }
}
?>
