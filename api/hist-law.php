<?php

require_once '../conn_db.php';
require_once __DIR__ . '/../providers/Headers.php';
require_once __DIR__ . '/../providers/Service.php';
require_once __DIR__ . '/../providers/Response.php';

$apiHeader = new Providers\ApiHeader();
$apiHeader->setHeaders();
$service = new Providers\Service($conn);
// Include database connection

// Request user profile
// check method
$apiHeader->checkMethod('GET');

// validate token
// Check for Authorization header
$headers = getallheaders();
// check authorization
$apiHeader->checkAuthorization();



$token = $apiHeader->validateToken($headers['Authorization']);

// If all checks pass, return user profile

$result = $service->getUser();

if (!$result) {
    Response::json(
        [
            'status' => 401,
            "message" => 'Token Expirces'
        ],
        401
    );
    exit;
}

$user = $service->user($result['user_id']);
// Get client input
$input = json_decode(file_get_contents('php://input'), true);


$id_law = $_GET['id']?? null;


if ($id_law) {
    $select_history = "SELECT * FROM reques_alaw WHERE student_id = ? AND user_id = ?";
    $stmt = $conn->prepare($select_history);
    $stmt->bind_param("ss", $user['student_id'], $id_law);
} else {
    $select_history = "SELECT * FROM reques_alaw WHERE student_id = ?";
    $stmt = $conn->prepare($select_history);
    $stmt->bind_param("s", $user['student_id']);
}
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'message' => 'No history found']);
    exit;
}
$history = $result->fetch_all(MYSQLI_ASSOC);



// Prepare user profile data
echo Response::json(
    [
        // 'success' => true,
        // 'message' => 'User profile and history retrieved successfully',
        // 'user' => $user,
        'history' => $history
    ]
);
// Close the statement and connection
$stmt->close();
$conn->close();
// End of the script
// Note: Ensure that the database connection and other required files are correctly set up in your environment.