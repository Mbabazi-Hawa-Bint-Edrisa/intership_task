<?php
require_once "../config/database.php";
require_once "../classes/User.php";

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$name = trim($input['name'] ?? '');
$age = (int)($input['age'] ?? 0);
$gender = $input['gender'] ?? '';
$country = trim($input['country'] ?? '');
$bio = trim($input['bio'] ?? '');

if (empty($name) || $age <= 0 || empty($gender) || empty($country) || empty($bio)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields correctly.']);
    exit;
}

$database = new Database();
$db = $database->connect();

$user = new User($db);

if ($user->register($name, $age, $gender, $country, $bio)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to register user.']);
}
