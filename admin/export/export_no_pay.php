<?php
require '../../vendor/autoload.php'; // Adjust the path to your autoload file if necessary

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

include '../../conn_db.php'; // Ensure no stray characters here

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
$search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
$search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

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

// Main query (no LIMIT or OFFSET)
$query = "SELECT s.student_id, s.name, s.lastname, s.skill, YEAR(s.stay) AS start_year, s.gender, s.dob, s.address, s.phone_student, s.education_level
              FROM register s 
              $where 
              ORDER BY s.student_id";

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
    $gender = $row['gender'];
    $dob = $row['dob'];
    $address = $row['address'];
    $phone_student = $row['phone_student'];
    $education_level = $row['education_level'];

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
        $status = isset($paymentsByYear[$year]) ? $paymentsByYear[$year] : 'Pedding';
        switch ($status) {
            case 'Approved':
            $message = 'បានបង់ប្រាក់រួចរាល់';
            break;
            case 'Reject':
            $message = 'បដិសេធ';
            break;
            case 'Pedding':
            $message = 'កំពុងរង់ចាំអនុម័ត';
            break;
            default:
            $message = 'មិនទាន់បានបង់ប្រាក់';
            break;
        }

        $rows[] = [
            'student_id' => $studentId,
            'name' => $name,
            'lastname' => $lastname,
            'skill' => $skill,
            'year' => $year,
            'message' => $message,
            'gender' => $gender,
            'dob' => $dob,
            'address' => $address,
            'phone_student' => $phone_student,
            'education_level' => $education_level,
            'status' => isset($paymentsByYear[$year]) ? $paymentsByYear[$year] : 'Not Paid'
        ];
    }
}

// Store data for use in HTML
$result = $rows;
// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$title = "បញ្ជីឈ្មោះនិស្សិតមិនទាន់បង់ប្រាក់"; // Title text
$sheet->mergeCells('A1:K1');
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
$sheet->getStyle('A1:L1')->applyFromArray($titleStyle);

// Set column headers
$headers = [
    'ល.រ', 'លេខសម្គាល់និស្សិត', 'ឈ្មោះនិស្សិត', 'ភេទ', 'ថ្ងៃខែឆ្នាំកំណើត', 'អាសយដ្ឋាន',
    'លេខទូរស័ព្ទនិស្សិត', 'ជំនាញ', 'កម្រិតសិក្សា', 'ឆ្នាំសិក្សា','សកម្មភាព'
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
$sheet->getStyle('A2:K2')->applyFromArray($headerStyle);

// Set table data
$rowIndex = 3;
$i = 1;
foreach ($result as $row) {
    $data = [
        $i++, $row['student_id'], $row['lastname'] . " " . $row['name'], $row['gender'],
        $row['dob'], $row['address'], $row['phone_student'], $row['skill'],
        $row['education_level'], $row['year'],
        ($row['status'] == "Pending" ? "កំពុងរង់ចាំពិនិត្យ" : ($row['status'] != "Not Paid" ? "បានដិសេធ" : "មិនទាន់បានបង់ប្រាក់"))
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
$sheet->getStyle('A2:K' . ($rowIndex - 1))->applyFromArray($styleArray);

// Set column widths
foreach (range('A', 'K') as $columnID) {
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
$sheet->getStyle('A3:K' . ($rowIndex - 1))->applyFromArray($recordStyle);

// Output to Excel
$writer = new Xlsx($spreadsheet);
$filename = 'students_nopay.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
