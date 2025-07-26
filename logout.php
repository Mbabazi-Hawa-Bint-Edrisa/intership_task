<?php
require_once 'User.php';
session_start();

$user = new User();
if (isset($_SESSION['user_id'])) {
    $user->clearAuthToken($_SESSION['user_id']);
    setcookie('auth_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

session_destroy();
header('Location: login.php');
exit;
?>