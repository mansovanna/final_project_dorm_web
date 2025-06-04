<?php

require_once __DIR__ . '/../providers/Headers.php';
require_once '../conn_db.php';

$apiHeader = new Providers\ApiHeader();
$apiHeader->setHeaders();
$apiHeader->checkMethod('GET');
$apiHeader->checkAuthorization();

$headers = getallheaders();
$token = $apiHeader->validateToken($headers['Authorization']);

// 1. Validate token against database
$select_token = "SELECT * FROM tokens WHERE token = ?";
$stmt = $conn->prepare($select_token);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}

$tokenData = $result->fetch_assoc();

function getStudentPaymentStatusPerYear($conn, $userId)
{
    // Get student registration info
    $registerQuery = "SELECT * FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $registerResult = $stmt->get_result();
    if (!$registerResult) {
        echo json_encode(['status' => 'error', 'message' => 'Student registration not found.']);
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
                    'room' => isset($row['room']) ? $row['room'] : null,
                    'accommodation_fee' => isset($row['accommodation_fee']) ? (int) $row['accommodation_fee'] : 0,
                    'discount' => isset($row['discount']) ? (int) $row['discount'] : 0,
                    'water_fee' => isset($row['water_fee']) ? (int) $row['water_fee'] : 0,
                    'electricity_fee' => isset($row['electricity_fee']) ? (int) $row['electricity_fee'] : 0,
                    'total_fee' =>$sql['total_fee'] ?? 0,
                    'payment_status' => $paymentStatus['status'],
                    'payment_date' => $paymentStatus['date'],
                    'payment_id' => $paymentStatus['id'],
                    'year' => $year,
                ];
        }
    }
    return $data;
}

$data = getStudentPaymentStatusPerYear($conn, $tokenData['user_id']);
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


if($data) {
    http_response_code(200);
    echo json_encode(['success' => true, 'data' => $data]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'No payment history found']);
}