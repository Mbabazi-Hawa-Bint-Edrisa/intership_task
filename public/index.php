
<?php
session_start();
require_once "../config/database.php";


$database = new Database();
$conn = $database->connect();
if (!$conn) {
    die("Connection failed: " . $conn->error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $gender = $_POST["gender"];
    $country = $_POST["country"];
    $bio = $_POST["bio"];

    try {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, gender, country, bio) 
                                VALUES (:fullname, :email, :password, :gender, :country, :bio)");
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':bio', $bio);
        
        if ($stmt->execute()) {
            $message = "Registration successful. Please <a href='login.php'>login</a>.";
        } else {
            $message = "Registration failed.";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>


<?php include "../includes/header.php"; ?>


<div class="form-container">
    
    <h2>Register</h2>
    <form method="POST" autocomplete="off">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>

        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <input type="text" name="country" placeholder="Country" required>
        <textarea name="bio" placeholder="Short Bio" rows="3" required></textarea>

        <button type="submit">Register</button>
    </form>
    <p class="msg"><?= $message ?></p>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php include "../includes/footer.php"; ?>
