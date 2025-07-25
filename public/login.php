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
    $email = trim($_POST["email"]);
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
    <form method="post" autocomplete="off">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login with Amtech</button>
    </form>
    <p class="msg error"><?= $error ?></p>
    <p class="link">Don't have an account? <a href="index.php">Create an account</a></p>
</div>

<!-- cookie consent alert -->
<div id="cookieConsent" style="position: fixed; bottom: 0; width: 100%; background: #222; color: white; padding: 15px; text-align: center; display: none; z-index: 1000;">
  This site uses cookies to ensure you get the best experience. 
  <button id="acceptCookies" style="margin-left: 15px; padding: 8px 12px; cursor: pointer;">Accept Cookies</button>
</div>

<script>
  // Checking if user already accepted cookies
  function getCookie(name) {
    let cookieArr = document.cookie.split(";");
    for(let i=0; i < cookieArr.length; i++) {
      let cookiePair = cookieArr[i].split("=");
      if(name === cookiePair[0].trim()) {
        return decodeURIComponent(cookiePair[1]);
      }
    }
    return null;
  }

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
  }

  window.onload = function() {
    if (!getCookie("cookiesAccepted")) {
      document.getElementById("cookieConsent").style.display = "block";
    }

    document.getElementById("acceptCookies").onclick = function() {
      setCookie("cookiesAccepted", "true", 365);
      document.getElementById("cookieConsent").style.display = "none";
    }
  }
</script>

<?php include "../includes/footer.php"; ?>
