<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$initials = strtoupper(substr($user['fullname'], 0, 1));
?>

<?php include "../includes/header.php"; ?>
<div class="dashboard">
    <div class="profile-circle"><?= $initials ?></div>
    <h2>Welcome, <?= htmlspecialchars($user['fullname']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']) ?></p>
    <p><strong>Country:</strong> <?= htmlspecialchars($user['country']) ?></p>
    <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
    <button onclick="confirmLogout()">Logout</button>
</div>

<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}
</script>
<?php include "../includes/footer.php"; ?>
