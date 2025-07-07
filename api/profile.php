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
$apiHeader->setHeaders();
// check method
$apiHeader->checkMethod('GET');

// validate token
// Check for Authorization header
// $headers = getallheaders();
// // check authorization
// $token = $apiHeader->checkAuthorization();



// $token = $apiHeader->validateToken($headers['Authorization']);

// If all checks pass, return user profile

 $result =$service->getUser();


echo json_encode([$result]);
