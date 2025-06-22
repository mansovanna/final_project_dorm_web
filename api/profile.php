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

$result =$service->getUser();

if(!$result){
    Response::json(
        [
            'status'=> 401,
            "message" => 'Token Expirces'
        ],
        401
    );
}else {
    $user = $service->user($result['user_id']);
    Response::json([
        'status' => 200,
        'message' =>"User Request success!",
        'user' => $user,
    ]);
}
