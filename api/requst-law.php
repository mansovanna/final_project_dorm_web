<?php

require_once __DIR__ . '/../providers/Headers.php';
$apiHeader = new Providers\ApiHeader();

$apiHeader->setHeaders();

// Include database connection
require_once '../conn_db.php';

// Check method
$apiHeader->checkMethod('POST');
// Check authorization
$apiHeader->checkAuthorization();

// Validate token
$headers = getallheaders();
$token = $apiHeader->validateToken($headers['Authorization']);

// Validate token in DB
$select_token = "SELECT * FROM tokens WHERE token = ?";
$stmt = $conn->prepare($select_token);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}
$tokenData = $result->fetch_assoc();

// Get user profile
$select_user = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($select_user);
$stmt->bind_param("s", $tokenData['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}
$user = $result->fetch_assoc();
unset($user['password']);

// Get client input
$input = json_decode(file_get_contents('php://input'), true);

if (empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No Request data from client!']);
    exit;
}

// Parse input
$student_id = $input['student_id'] ?? $user['student_id'];
$user_name = $input['user_name'] ?? $user['lastname']." ".$user['name'];
$sumday = $input['sumday'] ?? 0;
$first_date = $input['first_date'] ?? null;
$end_date = $input['end_date'] ?? null;
$reason = $input['reason'] ?? null;
$status = "រងចាំ";

// Basic validation
if (!$first_date || !$end_date || !$reason) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Insert into database
$insert = "INSERT INTO reques_alaw (student_id, user_name, sumday, first_date, end_date, reason) 
           VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert);
$stmt->bind_param("ssisss", $student_id, $user_name, $sumday, $first_date, $end_date, $reason);

if ($stmt->execute()) {
    // Get the last inserted ID
    $last_id = $conn->insert_id;

    // Prepare the response data
    $response_data = [
        'id' => $last_id,
        'student_id' => $student_id,
        'user_name' => $user_name,
        'sumday' => $sumday,
        'first_date' => $first_date,
        'end_date' => $end_date,
        'reason' => $reason,
        'status' => $status
    ];

    echo json_encode([
        'success' => true,
        'message' => "Leave request submitted successfully.",
        'request' => $response_data
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Database error: " . $stmt->error
    ]);
}


// Clean up
$stmt->close();
$conn->close();
