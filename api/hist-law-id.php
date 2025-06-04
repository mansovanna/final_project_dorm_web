<?php 

require_once __DIR__ . '/../providers/Headers.php';
$apiHeader = new Providers\ApiHeader();

$apiHeader->setHeaders();

// Include database connection
require_once '../conn_db.php';
// Request user profile
// check method
$apiHeader->checkMethod('GET');
// check authorization
$apiHeader->checkAuthorization();
// validate token
// Check for Authorization header
$headers = getallheaders();
$token = $apiHeader->validateToken($headers['Authorization']);
// If all checks pass, return user profile
// check user token
$token = $apiHeader->validateToken($headers['Authorization']);
// select check user token
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
$token = $result->fetch_assoc();

// Get client input
$input = json_decode(file_get_contents('php://input'), true);


// Get user profile
$select_user = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($select_user);
$stmt->bind_param("s", $token['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}
$user = $result->fetch_assoc();
unset($user['password']);

// Get history of law
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No Request data from client!']);
    exit;
}

$id_law = $input['id']??0;


$select_history = "SELECT * FROM reques_alaw WHERE student_id = ? AND user_id = ?";
$stmt = $conn->prepare($select_history);
$stmt->bind_param("ss", $token['user_id'], $id_law);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'message' => 'No history found']);
    exit;
}
$history = $result->fetch_all(MYSQLI_ASSOC);



// Prepare user profile data
echo json_encode([
    'success' => true,
    'message' => 'User profile and history retrieved successfully',
    // 'user' => $user,
    'history' => $history
]);
// Close the statement and connection
$stmt->close();
$conn->close();
// End of the script
// Note: Ensure that the database connection and other required files are correctly set up in your environment.