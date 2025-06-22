<?php 

use Providers\ApiHeader;
// use Providers\Response;

require_once __DIR__ . './../providers/Headers.php';
require_once __DIR__ . './../providers/Providers.php';

$header = new ApiHeader();
$header->setHeaders();
// Include database connection
require_once '../conn_db.php';


$header->checkMethod('POST');


// request body from the client
$input = json_decode(file_get_contents('php://input'), true);

if(empty($input)) {
    echo Response::json(['success' => false, 'message' => 'Student ID and password are required'], 400);
    exit;
}


// Start login process
if(!$conn){
    echo Response::json(['success' => false, 'message' => 'Database connection failed'], 500);
    exit;
}

// Block direct access to this file
require_once  __DIR__ . '/../providers/Service.php';
$service = new Providers\Service($conn);
$response = $service->login($input);

echo $response;
