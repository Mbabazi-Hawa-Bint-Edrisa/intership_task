<?php
require_once 'User.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// CSRF token handling
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Route handling
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($request, '/'));
$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

$user = new User();

switch ($resource) {
    case 'csrf-token':
        echo json_encode(['csrf_token' => $_SESSION['csrf_token']]);
        break;

    case 'users':
        $method = $_SERVER['REQUEST_METHOD'];
        $csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

        if ($method !== 'GET' && !validateCsrfToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'Invalid CSRF token']);
            exit;
        }

        try {
            switch ($method) {
                case 'GET':
                    echo json_encode($user->getAll());
                    break;

                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Name, email, and password are required']);
                        exit;
                    }
                    echo json_encode($user->create($data['name'], $data['email'], $data['password']));
                    break;

                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['error' => 'User ID is required']);
                        exit;
                    }
                    $data = json_decode(file_get_contents('php://input'), true);
                    if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'Name, email, and password are required']);
                        exit;
                    }
                    echo json_encode($user->update($id, $data['name'], $data['email'], $data['password']));
                    break;

                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['error' => 'User ID is required']);
                        exit;
                    }
                    echo json_encode($user->delete($id));
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(['error' => 'Method not allowed']);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Resource not found']);
}
?>