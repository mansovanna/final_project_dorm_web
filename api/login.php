<?php 

use Providers\ApiHeader;

require_once __DIR__ . './../providers/Headers.php';
$header = new ApiHeader();
$header->setHeaders();
// Include database connection
require_once '../conn_db.php';


$header->checkMethod('POST');


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
