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
$apiHeader->checkMethod('POST');

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


// Block to handle payment request
$paymentData = $_POST;

// If you expect an image to be uploaded, it should come via $_FILES, not $_POST
if (isset($_FILES['image'])) {
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $paymentData['image_url'] = null;
        $paymentData['image_error'] = $_FILES['image']['error'];
    } else {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $paymentData['image_url'] = '/uploads/' . $filename;
        } else {
            $paymentData['image_url'] = null;
            $paymentData['image_error'] = 'move_uploaded_file_failed';
        }
    }
} else {
    $paymentData['image_url'] = null;
    $paymentData['image_error'] = 'no_file_uploaded';
}


// chceck image uploading ready


if ($paymentData != null) {

    $insert_data = "INSERT INTO payment (
        student_id, user_name, building, room_number, status, image, date
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($insert_data);
    $status = 'pending';
    $image_url = $paymentData['image_url'];
    $date = $paymentData['year'];

    $stmt->bind_param(
        "sssssss",
        $user['student_id'],
        $user['name'],
        $user['building'],
        $user['room'],
        $status,
        $image_url,
        $date
    );

    

    $executeResult = $stmt->execute();

    if ($executeResult) {
        Response::json([
            "message" => "Payment uploaded successfully",
            "success" => true
        ], 200);
        exit;
    } else {
        Response::json([
            "message" => "Failed to upload payment",
            "success" => false,
            "error" => $stmt->error
        ], 500);
        exit;
    }
}


echo Response::json(
    [
        'message' => "Felase upload data"
    ],
    401
);

exit;