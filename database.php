<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'amtech_db';
    private $username = 'api_user';
    private $password = 'secure_password';
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
            exit;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>