<?php
require_once "../config/database.php";
require_once "../classes/User.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $age = (int) $_POST['age'];
    $gender = $_POST['gender'];
    $country = trim($_POST['country']);
    $bio = trim($_POST['bio']);

    if (empty($name) || $age <= 0 || empty($gender) || empty($country) || empty($bio)) {
        die("Please fill in all fields correctly.");
    }

    $database = new Database();
    $db = $database->connect();

    $user = new User($db);
    if ($user->register($name, $age, $gender, $country, $bio)) {
        echo "✅ User registered successfully!";
    } else {
        echo "❌ Failed to register user.";
    }
}
?>
<br><br>
<a href="index.php">Back to Form</a>
