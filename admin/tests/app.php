<?php
session_start();
// block content of app.php
require_once '../../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}


// check connection database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get all payment history
header('Content-Type: application/json');
// method header allow
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type, Authorization');
// check method request

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    echo json_encode([
        'status' => true,
        'message' => 'Request successful.',
        // 'data' => getStudentPaymentStatusPerYear($conn)
        'data' => getDataServer($conn),
    ]);
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo json_encode([
        'status' => true,
        'message' => 'Payment history retrieved successfully.',
        'data' => "Method POST is not allowed, please use GET method.",
    ]);
}

// select all payment history and student info is not payment
function getUnpaidYears($conn)
{
    $currentYear = date('Y');

    // 1. Get all students with start_year
    $query = "SELECT s.student_id, s.name, s.lastname, YEAR(s.stay) AS start_year 
              FROM register s";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . mysqli_error($conn)
        ];
    }

    $students = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $studentId = $row['student_id'];
        $startYear = $row['start_year'];

        // 2. Get paid years for the student
        $paymentQuery = "SELECT DISTINCT YEAR(payment_date) AS paid_year 
                         FROM payment 
                         WHERE student_id = '$studentId'";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        $paidYears = [];
        while ($payRow = mysqli_fetch_assoc($paymentResult)) {
            $paidYears[] = $payRow['paid_year'];
        }

        // 3. Create range from start year to current year
        $expectedYears = range($startYear, $currentYear);

        // 4. Find unpaid years
        $unpaidYears = array_diff($expectedYears, $paidYears);

        $students[] = [
            'student_id' => $studentId,
            'name' => $row['name'],
            'lastname' => $row['lastname'],
            'start_year' => $startYear,
            'unpaid_years' => array_values($unpaidYears)
        ];
    }

    return [
        'status' => 'success',
        'data' => $students
    ];
}


function getStudentPaymentStatusPerYear($conn)
{
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
    $search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
    $search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

    $offset = ($page - 1) * $limit;

    $currentYear = date('Y');

    // Create dynamic WHERE clause
    $where = "WHERE 1=1";
    if (!empty($search_student_id)) {
        $where .= " AND s.student_id LIKE '%$search_student_id%'";
    }
    if (!empty($search_skill)) {
        $where .= " AND s.skill LIKE '%$search_skill%'";
    }

    // Get total count (for pagination)
    $countQuery = "SELECT COUNT(*) AS total FROM register s $where";
    $countResult = mysqli_query($conn, $countQuery);
    $total = mysqli_fetch_assoc($countResult)['total'];

    // Main query
    $query = "SELECT s.student_id, s.name, s.lastname, s.skill, YEAR(s.stay) AS start_year 
              FROM register s 
              $where 
              ORDER BY s.student_id 
              LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . mysqli_error($conn)
        ];
    }

    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $studentId = $row['student_id'];
        $name = $row['name'];
        $lastname = $row['lastname'];
        $skill = $row['skill'];
        $startYear = $row['start_year'];

        // Optional year filter: skip years not matching the filter
        $fromYear = $startYear;
        $toYear = $currentYear;
        if ($search_year > 0) {
            $fromYear = $toYear = $search_year;
        }

        // Get payments per student
        $paymentQuery = "SELECT YEAR(payment_date) AS paid_year, status 
                         FROM payment 
                         WHERE student_id = '$studentId'";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        $paymentsByYear = [];
        while ($payRow = mysqli_fetch_assoc($paymentResult)) {
            $paymentsByYear[$payRow['paid_year']] = $payRow['status'];
        }

        // Build rows per year
        for ($year = $fromYear; $year <= $toYear; $year++) {
            $message = 'មិនទាន់បានបង់ប្រាក់';
            if (isset($paymentsByYear[$year]) && $paymentsByYear[$year] === 'Approved') {
                $message = 'បានបង់ប្រាក់រួចរាល់';
            }

            $rows[] = [
                'student_id' => $studentId,
                'name' => $name,
                'lastname' => $lastname,
                'skill' => $skill,
                'year' => $year,
                'message' => $message
            ];
        }
    }

    return [
        'status' => 'success',
        'total' => $total,
        'page' => $page,
        'limit' => $limit,
        'data' => $rows
    ];
}




function oldCode($conn)
{
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
    $search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
    $search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

    $offset = ($page - 1) * $limit;

    $sql_select = "
    SELECT r.*
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";
    if ($search_student_id) {
        $sql_select .= " AND r.student_id LIKE '%$search_student_id%'";
    }
    if ($search_skill) {
        $sql_select .= " AND r.skill = '$search_skill'";
    }
    if ($search_year) {
        $sql_select .= " AND r.year = '$search_year'";
    }
    $sql_select .= " LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql_select);
    $unlisted_students_count = $result->num_rows;

    $sql_count = "
    SELECT COUNT(*) as count
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";
    if ($search_student_id) {
        $sql_count .= " AND r.student_id LIKE '%$search_student_id%'";
    }
    if ($search_skill) {
        $sql_count .= " AND r.skill = '$search_skill'";
    }
    if ($search_year) {
        $sql_count .= " AND r.year = '$search_year'";
    }
    $count_result = $conn->query($sql_count);
    $total_rows = $count_result->fetch_assoc()['count'];
    $pages = ceil($total_rows / $limit);

}




require '../../vendor/autoload.php'; // Adjust the path to your autoload file if necessary

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

function export()
{
    global $conn;
    include '../../conn_db.php'; // Ensure no stray characters here

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    export();

    // Set the content type to Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="students_nopay.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1'); // If you're serving to IE 9, otherwise remove this line
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Always modified
    header('Pragma: public'); // HTTP/1.0
// Fetch data from the database
    $search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
    $search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
    $search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

    // Build the SQL query with search parameters
    $sql_select = "
    SELECT r.*
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";

    if ($search_student_id) {
        $sql_select .= " AND r.student_id LIKE '%$search_student_id%'";
    }
    if ($search_skill) {
        $sql_select .= " AND r.skill = '$search_skill'";
    }
    if ($search_year) {
        $sql_select .= " AND r.year = '$search_year'";
    }

    $result = $conn->query($sql_select);

    // Create new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set title
    $title = "បញ្ជីឈ្មោះនិស្សិតមិនទាន់បង់ប្រាក់"; // Title text
    $sheet->mergeCells('A1:J1');
    $sheet->setCellValue('A1', $title);

    // Style title
    $titleStyle = [
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['argb' => 'FF000000'],
            'name' => 'Khmer OS Muol Light', // Specify your desired font family
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FFFFFFFF'],
        ],
        'borders' => [
            'bottom' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];
    $sheet->getStyle('A1:J1')->applyFromArray($titleStyle);

    // Set column headers
    $headers = [
        'ល.រ',
        'លេខសម្គាល់និស្សិត',
        'ឈ្មោះនិស្សិត',
        'ភេទ',
        'ថ្ងៃខែឆ្នាំកំណើត',
        'អាសយដ្ឋាន',
        'លេខទូរស័ព្ទនិស្សិត',
        'ជំនាញ',
        'កម្រិតសិក្សា',
        'ឆ្នាំសិក្សា'
    ];
    $sheet->fromArray($headers, NULL, 'A2');

    // Style header row
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['argb' => 'FFFFFFFF'],
            'name' => 'Khmer OS Siemreap', // Specify your desired font family
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT, // Align header text to the left
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF4F81BD'],
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000'],
            ],
        ],
    ];
    $sheet->getStyle('A2:J2')->applyFromArray($headerStyle);

    // Set table data
    $rowIndex = 3;
    $i = 1;
    while ($row = $result->fetch_assoc()) {
        $data = [
            $i++,
            $row['student_id'],
            $row['lastname'] . " " . $row['name'],
            $row['gender'],
            $row['dob'],
            $row['address'],
            $row['phone_student'],
            $row['skill'],
            $row['education_level'],
            $row['year']
        ];
        $sheet->fromArray($data, NULL, 'A' . $rowIndex++);
    }

    // Apply borders to the entire table
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
            ],
        ],
    ];
    $sheet->getStyle('A2:J' . ($rowIndex - 1))->applyFromArray($styleArray);

    // Set column widths
    foreach (range('A', 'J') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set font family for records and align left
    $recordStyle = [
        'font' => [
            'name' => 'Khmer OS Siemreap', // Specify your desired font family
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_LEFT,
        ],
    ];
    $sheet->getStyle('A3:J' . ($rowIndex - 1))->applyFromArray($recordStyle);

    // Output to Excel
    $writer = new Xlsx($spreadsheet);
    $filename = 'students_nopay.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;


}

function getDataServer($conn)
{
    // Pagination and search filters
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
    $search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
    $search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

    $offset = ($page - 1) * $limit;

    // Build SQL with search filters
    $where = [];
    if (!empty($search_student_id)) {
        $where[] = "student_id LIKE '%$search_student_id%'";
    }
    if (!empty($search_skill)) {
        $where[] = "skill LIKE '%$search_skill%'";
    }
    if (!empty($search_year)) {
        $where[] = "year = '$search_year'";
    }
    $whereClause = '';
    if (!empty($where)) {
        $whereClause = 'WHERE ' . implode(' AND ', $where);
    }

    $sql = "SELECT * FROM register $whereClause";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . mysqli_error($conn)
        ];
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Get start year from 'stay' field
        $startYear = is_numeric($row['stay']) ? (int) $row['stay'] : (int) date('Y', strtotime($row['stay']));
        $currentYear = (int) date('Y');
        $years = [];
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }

        // For each year, add a record with user info and payment status
        foreach ($years as $year) {
            $paymentStatus = getPaymentStatusByYear($conn, $row['student_id'], $year);
           
            if ($row['year'] != (int)$paymentStatus['date'] && $paymentStatus['status'] === 'Approved') {
                // If the student has paid for this year, skip adding them
                continue;
            } else {
                $data[] = [
                    'student_id' => $row['student_id'],
                    'name' => $row['name'],
                    'lastname' => $row['lastname'],
                    'gender' => $row['gender'],
                    'dob' => $row['dob'],
                    'stay' => date('Y', strtotime($row['stay'])),
                    'building' => $row['building'],
                    'room_number' => $row['room'],
                    'phone_student' => $row['phone_student'],
                    'year' => $year,
                    'payment_data' => $paymentStatus,
                ];

                return json_encode($data);
            } 
           
            
        }
    }

    return $data;
}
getDataServer($conn);

// Helper function to get payment status for a student in a specific year
function getPaymentStatusByYear($conn, $student_id, $year)
{
    $sql = "SELECT status, date FROM payment 
            WHERE student_id = '" . mysqli_real_escape_string($conn, $student_id) . "' 
            AND `date` = '" . intval($year) . "'";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return [
            'status' => $row['status'],
            'date' => $row['date']?? null,
        ];
    }

    return [
        'status' => null,
        'date' => null,
    ];
}
