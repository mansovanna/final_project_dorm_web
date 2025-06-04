<?php
require_once __DIR__ . '/../providers/Headers.php';
require_once __DIR__ . '/../providers/Service.php';
require_once __DIR__ . '/../conn_db.php';

use Providers\ApiHeader;
use Providers\Service;

$apiHeader = new ApiHeader();
$apiHeader->setHeaders();
$apiHeader->checkMethod('POST');
$apiHeader->checkAuthorization();

$headers = getallheaders();
$authorizationHeader = $headers['Authorization'] ?? '';
$token = $apiHeader->validateToken($authorizationHeader);

$stmt = $conn->prepare("SELECT * FROM tokens WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$userId = $result->fetch_assoc()['user_id'];
$requestService = new Service($conn);



// Block to handle payment request
$paymentData = $_POST;

// If you expect an image to be uploaded, it should come via $_FILES, not $_POST
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Example: Save the uploaded image to a directory
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
    }
} else {
    $paymentData['image_url'] = null;
}
echo json_encode([
    'success' => true,
    'message' => 'Payment request received',
    'data' => $paymentData
]);