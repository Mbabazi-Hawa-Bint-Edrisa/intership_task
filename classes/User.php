<?php
require_once "Person.php";

class User extends Person {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($name, $age, $gender, $country, $bio) {
        $this->setData($name, $age, $gender, $country, $bio);

        $sql = "INSERT INTO users (name, age, gender, country, bio) 
                VALUES (:name, :age, :gender, :country, :bio)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":age", $this->age);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":country", $this->country);
        $stmt->bindParam(":bio", $this->bio);
        
        return $stmt->execute();
    }
}
