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



function getStudentPaymentStatusPerYear($conn, $user)
{
    $string = (String) $user['student_id'];
    // Get student registration info
    $registerQuery = "SELECT * FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param("s", $string);
    $stmt->execute();
    $registerResult = $stmt->get_result();
    if (!$registerResult) {
        echo Response::json(['status' => 'error', 'message' => 'Student registration not found.'], 401);
        exit();
    }

    $data = [];
    while ($row = $registerResult->fetch_assoc()) {
        $startYear = is_numeric($row['stay']) ? (int) $row['stay'] : (int) date('Y', strtotime($row['stay']));
        $currentYear = (int) date('Y');
        $years = [];
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }
        foreach ($years as $year) {
            $paymentStatus = getPaymentStatusByYear($conn, $row['student_id'], $year);

            $data[$year] = [
                'student_id' => $row['student_id'],
                'lastname' => $row['lastname'],
                'name' => $row['name'],
                'building' => $row['building'],
                'stay' => $row['stay'],
                'skill' => $row['skill'],
                'address' => $row['address'],
                'education_level' => $row['education_level'],
                'room' => $row['room'] ?? null,
                'accommodation_fee' => (int) ($row['accommodation_fee'] ?? 0),
                'discount' => (int) ($row['discount'] ?? 0),
                'water_fee' => (int) ($row['water_fee'] ?? 0),
                'electricity_fee' => (int) ($row['electricity_fee'] ?? 0),
                'total_fee' => $row['total_fee'] ?? 0,
                'payment_status' => $paymentStatus['status'],
                'payment_date' => $paymentStatus['date'],
                'payment_id' => $paymentStatus['id'],
                'year' => $year,
            ];
        }
    }
    return $data;
}


// echo json_encode($data);
function getPaymentStatusByYear($conn, $student_id, $year)
{
    $sql = "SELECT id, status, date FROM payment 
            WHERE student_id = '" . mysqli_real_escape_string($conn, $student_id) . "' 
            AND `date` = '" . intval($year) . "'";

    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return [
            'status' => $row['status'],
            'date' => $row['date'] ?? null,
            'id' => $row['id'] ?? null,
        ];
    }

    return [
        'status' => null,
        'date' => null,
        'id' => null,
    ];
}


$id = $_GET['id'] ?? null;
$data = []; // Initialize $data to avoid undefined variable warning

if ($id || !empty($id)) {

    $select_all = 'SELECT * FROM payment WHERE student_id =?';
    $stmt = $conn->prepare($select_all);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payments = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

} else {
    // Get All Product 
    $data = getStudentPaymentStatusPerYear($conn, $user);
}

if ($data) {
    echo Response::json(
        ['success' => true, 'data' => $data],
        200
    );
}
echo Response::json(
    ['success' => false, 'message' => 'No payment history found'],
    200
);

exit;