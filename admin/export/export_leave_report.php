<?php
require '../../vendor/autoload.php'; // Adjust the path to your autoload file if necessary

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

include '../../conn_db.php';

// Increase memory limit and execution time to handle large data
ini_set('memory_limit', '512M');
set_time_limit(300); // Extend the script execution time

// Fetch data from the database
$first_date = isset($_GET['first_date']) ? $_GET['first_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';

// Base SQL query
$sql = "SELECT lr.student_id, lr.user_name, r.skill,
                MIN(lr.first_date) as first_date, 
                MAX(lr.end_date) as end_date, 
                GROUP_CONCAT(lr.reason SEPARATOR ', ') as reason, 
                COUNT(lr.reason) as leave_count, 
                lr.status 
        FROM reques_alaw lr
        JOIN register r ON lr.student_id = r.student_id
        WHERE lr.status = 'អនុញ្ញាត'";

// Append search filters to the SQL query
if ($first_date) {
    $first_date = date('Y-m-d', strtotime($first_date));
    $sql .= " AND lr.first_date >= '" . $conn->real_escape_string($first_date) . " 00:00:00'";
}
if ($end_date) {
    $end_date = date('Y-m-d', strtotime($end_date));
    $sql .= " AND lr.end_date <= '" . $conn->real_escape_string($end_date) . " 23:59:59'";
}
if ($student_id) {
    $sql .= " AND lr.student_id LIKE '%" . $conn->real_escape_string($student_id) . "%'";
}
if ($skill) {
    $sql .= " AND r.skill LIKE '%" . $conn->real_escape_string($skill) . "%'";
}

// Group by student_id to avoid duplicate entries
$sql .= " GROUP BY lr.student_id";

$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$title = "បញ្ជីឈ្មោះនិស្សិតសុំច្បាប់";
$sheet->mergeCells('A1:E1');
$sheet->setCellValue('A1', $title);

// Style title
$titleStyle = [
    'font' => [
        'bold' => true,
        'size' => 16,
        'color' => ['argb' => 'FF000000'],
        'name' => 'Khmer OS Muol Light',
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
$sheet->getStyle('A1:E1')->applyFromArray($titleStyle);

// Set column headers
$headers = ['ល.រ', 'លេខសម្គាល់និស្សិត', 'ឈ្មោះនិស្សិត', 'ជំនាញ', 'ចំនួនការសុំច្បាប់'];
$sheet->fromArray($headers, NULL, 'A2');

// Style header row
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => 'FFFFFFFF'],
        'name' => 'Khmer OS Siemreap',
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
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
$sheet->getStyle('A2:E2')->applyFromArray($headerStyle);

// Set table data
$rowIndex = 3;
$i = 1;
while ($row = $result->fetch_assoc()) {
    $data = [
        $i++, 
        $row['student_id'], 
        $row['user_name'], 
        $row['skill'], 
        $row['leave_count']
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
$sheet->getStyle('A2:E' . ($rowIndex - 1))->applyFromArray($styleArray);

// Set column widths
foreach (range('A', 'E') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set font family for records and align left
$recordStyle = [
    'font' => [
        'name' => 'Khmer OS Siemreap',
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
];
$sheet->getStyle('A3:E' . ($rowIndex - 1))->applyFromArray($recordStyle);

// Flush the output buffer to avoid extra data being sent before the file
if (ob_get_contents()) {
    ob_end_clean();
}

// Output to Excel
$writer = new Xlsx($spreadsheet);
$filename = 'students_leave_report.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
?>
