CREATE DATABASE IF NOT EXISTS amtech_db;
USE amtech_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    auth_token VARCHAR(255),
    auth_token_expires TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP USER IF EXISTS 'api_user'@'localhost';
CREATE USER 'api_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON amtech_db.users TO 'api_user'@'localhost';
FLUSH PRIVILEGES;