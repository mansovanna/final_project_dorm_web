<?php
require_once '../conn_db.php';
require_once __DIR__ . '/../providers/Headers.php';
require_once __DIR__ . '/../providers/Service.php';
require_once __DIR__ . '/../providers/Response.php';

$apiHeader = new Providers\ApiHeader();
$apiHeader->setHeaders();
$apiHeader->checkMethod('GET');

// Validate Authorization header and token
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization header missing']);
    exit;
}

$token = $apiHeader->checkAuthorization();
if (!$token || !$apiHeader->validateToken($token)) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired token']);
    exit;
}

$service = new Providers\Service($conn);
$result = $service->getUser();

echo json_encode($result);
