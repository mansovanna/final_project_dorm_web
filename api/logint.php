<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once '../conn_db.php';


if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// request body from the client
$input = json_decode(file_get_contents('php://input'), true);

if(empty($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Student ID and password are required']);
    exit;
}


// Start login process
if(!$conn){
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Block direct access to this file
require_once  __DIR__ . '/../providers/Service.php';
$service = new Providers\Service($conn);
$response = $service->login($input);

echo $response;
