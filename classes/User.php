<?php
require_once "Person.php";

class User extends Person {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($name, $gender, $country, $bio, $email, $password) {
        $this->setData($name, $gender, $country, $bio, $email, $password);

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, gender, country, bio, email, password) 
                VALUES (:name, :gender, :country, :bio, :email, :password)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":bio", $this->bio);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $hashedPassword);

        return $stmt->execute();
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
