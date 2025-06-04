<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../conn_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$student_id = $input['student_id'] ?? '';
$password = $input['password'] ?? '';

if (empty($student_id) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Student ID and password are required']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM register WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Generate a random token (can use more secure method or JWT)
    $token = bin2hex(random_bytes(32));

    // Save token to database for this user
    $update = $conn->prepare("UPDATE register SET token = ? WHERE user_id = ?");
    $update->bind_param("si", $token, $user['user_id']);
    $update->execute();

    echo json_encode([
        'success' => true,
        'token' => $token,
        'user' => [
            'id' => $user['user_id'],
            'student_id' => $user['student_id'],
            'name' => $user['name'] ?? null
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
$stmt->close();









// ------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}
// Check for Authorization header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authorization header missing']);
    exit;
}

list($type, $token) = explode(' ', $headers['Authorization'], 2);
if (strcasecmp($type, 'Bearer') !== 0 || empty($token)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid authorization header']);
    exit;
}