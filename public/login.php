<?php
session_start();
require_once "../config/database.php";

$database = new Database();
$conn = $database->connect();
if (!$conn) {
    die("Connection failed");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION["user"] = $user;
            setcookie("user", json_encode($user), time() + (86400 * 7), "/");
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<?php include "../includes/header.php"; ?>
<div class="form-container">
    <h2>Login</h2>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="msg error"><?= $error ?></p>
    <p class="link">Don't have an account? <a href="index.php">Create an account</a></p>
</div>
<?php include "../includes/footer.php"; ?>