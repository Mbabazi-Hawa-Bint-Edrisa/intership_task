<?php
// Start session
session_start();

// SQLite database configuration
define('DB_PATH', 'amtech_db.sqlite');

// Database connection
try {
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        remember_token TEXT
    )");
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle login
if (isset($_POST['login_amtech'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, email, password, username FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Set cookie for "remember me" functionality
            if (isset($_POST['remember_me'])) {
                $token = bin2hex(random_bytes(32));
                setcookie('amtech_auth', $token, time() + (30 * 24 * 60 * 60), "/"); // 30 days
                // Store token in database
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->execute([$token, $user['id']]);
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password";
        }
    }
}

// Handle account creation
if (isset($_POST['register'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate input
    if (empty($username) || empty($email) || empty($_POST['password'])) {
        $error = "Please fill in all fields";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email already exists";
        } else {
            // Create new user
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $password])) {
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Registration failed";
            }
        }
    }
}

// Check for cookie-based login
if (!isset($_SESSION['user_id']) && isset($_COOKIE['amtech_auth'])) {
    $token = $_COOKIE['amtech_auth'];
    $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amtech Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login with Amtech</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember_me"> Remember me</label>
        </div>
        <button type="submit" name="login_amtech">Login with Amtech</button>
    </form>

    <h2>Create Account</h2>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" name="register">Create Account</button>
    </form>
</body>
</html>