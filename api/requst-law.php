<?php

// use Providers\ApiHeader;
// use Providers\Service;
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
$apiHeader->checkMethod('POST');

// // validate token
// // Check for Authorization header
// $headers = getallheaders();
// // check authorization
// $apiHeader->checkAuthorization();



// $token = $apiHeader->validateToken($headers['Authorization']);

// // If all checks pass, return user profile

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

// $user = $service->user($result['user_id']);



// Get client input
$input = json_decode(file_get_contents('php://input'), true);

if (empty($input)) {

   Response::json(
       ['success' => false, 'message' => 'No Request data from client!'], 400
   );
   exit;
}

// Parse input
$student_id = $input['student_id'] ?? $result['student_id'];
$user_name = $input['user_name'] ?? $result['username'];
$sumday = $input['sumday'] ?? 0;
$first_date = $input['first_date'] ?? null;
$end_date = $input['end_date'] ?? null;
$reason = $input['reason'] ?? null;
$status = "រងចាំ";

// Basic validation
if (!$first_date || !$end_date || !$reason) {
    Response::json(
        ['success' => false, 'message' => 'Missing required fields'], 400
    );
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

    Response::json(
        [
            'success' => true,
            'message' => "Leave request submitted successfully.",
            'request' => $response_data
        ]
    );
} else {

    Response::json([
        'success' => false,
        'message' => "Database error: " . $stmt->error
    ], 500);
}


// Clean up
$stmt->close();
$conn->close();
