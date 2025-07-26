<?php
require_once 'User.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$user = new User();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $userData = $user->authenticate($email, $password);
        if ($userData) {
            // Existing user
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['name'];
            $_SESSION['user_email'] = $userData['email'];

            if ($remember) {
                $tokenData = $user->generateAuthToken($userData['id']);
                setcookie('auth_token', $tokenData['token'], [
                    'expires' => strtotime($tokenData['expires']),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }
            header('Location: dashboard.php');
            exit;
        } else {
            // Create new account
            try {
                $name = $_POST['name'] ?? 'User_' . substr(md5($email), 0, 8);
                $userData = $user->create($name, $email, $password);
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['user_name'] = $userData['name'];
                $_SESSION['user_email'] = $userData['email'];

                if ($remember) {
                    $tokenData = $user->generateAuthToken($userData['id']);
                    setcookie('auth_token', $tokenData['token'], [
                        'expires' => strtotime($tokenData['expires']),
                        'path' => '/',
                        'secure' => true,
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]);
                }
                header('Location: dashboard.php');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
}

// Check for auth token
if (isset($_COOKIE['auth_token'])) {
    $userData = $user->validateAuthToken($_COOKIE['auth_token']);
    if ($userData) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_name'] = $userData['name'];
        $_SESSION['user_email'] = $userData['email'];
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Amtech</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 8px; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login with Amtech</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="name">Name (optional for login, required for signup)</label>
            <input type="text" id="name" name="name" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="remember"> Remember me</label>
        </div>
        <button type="submit">Login with Amtech</button>
    </form>
</body>
</html>