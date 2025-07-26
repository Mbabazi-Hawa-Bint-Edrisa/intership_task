<?php
require_once 'Database.php';

class User {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT id, name, email, created_at FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function create($name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword]);
            return ['id' => $this->pdo->lastInsertId(), 'name' => $name, 'email' => $email];
        } catch (PDOException $e) {
            throw new Exception('Email already exists', 400);
        }
    }

    public function update($id, $name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
            $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword, 'id' => $id]);
            if ($stmt->rowCount() === 0) {
                throw new Exception('User not found', 404);
            }
            return ['id' => $id, 'name' => $name, 'email' => $email];
        } catch (PDOException $e) {
            throw new Exception('Email already exists', 400);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new Exception('User not found', 404);
        }
        return ['message' => 'User deleted'];
    }

    public function authenticate($email, $password) {
        $stmt = $this->pdo->prepare("SELECT id, name, email, password FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']];
        }
        return false;
    }

    public function generateAuthToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        $stmt = $this->pdo->prepare("UPDATE users SET auth_token = :token, auth_token_expires = :expires WHERE id = :id");
        $stmt->execute(['token' => $token, 'expires' => $expires, 'id' => $userId]);
        return ['token' => $token, 'expires' => $expires];
    }

    public function validateAuthToken($token) {
        $stmt = $this->pdo->prepare("SELECT id, name, email FROM users WHERE auth_token = :token AND auth_token_expires > NOW()");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clearAuthToken($userId) {
        $stmt = $this->pdo->prepare("UPDATE users SET auth_token = NULL, auth_token_expires = NULL WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }
}
?>