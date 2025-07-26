<?php
require_once 'User.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['auth_token'])) {
        $user = new User();
        $userData = $user->validateAuthToken($_COOKIE['auth_token']);
        if ($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_name'] = $userData['name'];
            $_SESSION['user_email'] = $userData['email'];
        } else {
            header('Location: login.php');
            exit;
        }
    } else {
        header('Location: login.php');
        exit;
    }
}

$user = new User();
$totalUsers = $user->countUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amtech Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; }
        .dashboard { display: flex; flex-direction: column; gap: 20px; }
        .card { background-color: #f8f9fa; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .card h3 { margin-top: 0; }
        .nav-links a { margin-right: 15px; color: #007bff; text-decoration: none; }
        .nav-links a:hover { text-decoration: underline; }
        .logout { color: #dc3545; }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="nav-links">
            <a href="index.php">Manage Users</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <div class="card">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
            <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <p>Account created: <?php echo htmlspecialchars($_SESSION['user_created_at'] ?? 'N/A'); ?></p>
        </div>
        <div class="card">
            <h3>System Overview</h3>
            <p>Total Users: <?php echo $totalUsers; ?></p>
        </div>
    </div>
</body>
</html>