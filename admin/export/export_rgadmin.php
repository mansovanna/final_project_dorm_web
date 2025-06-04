<?php
require '../../vendor/autoload.php'; // Adjust the path to your autoload file if necessary

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

include '../../conn_db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';
$student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';

// Build the SQL query with search parameters
$conditions = ["status = 'រង់ចាំ'"];
if ($skill) {
    $conditions[] = "skill = '$skill'";
}
if ($year) {
    $conditions[] = "year = '$year'";
}
if ($student_id) {
    $conditions[] = "student_id LIKE '%$student_id%'";
}
$sql_select = "SELECT * FROM register WHERE " . implode(" AND ", $conditions);

$result = $conn->query($sql_select);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$title = "បញ្ជីឈ្មោះនិស្សិតស្នើសុំស្នាក់នៅ"; // Title text
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
    'ល.រ', 'លេខសម្គាល់និស្សិត', 'ឈ្មោះនិស្សិត', 'ភេទ', 'ថ្ងៃខែឆ្នាំកំណើត', 'អាសយដ្ឋាន',
    'លេខទូរស័ព្ទនិស្សិត', 'ជំនាញ', 'កម្រិតសិក្សា', 'ឆ្នាំសិក្សា'
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
        $i++, $row['student_id'], $row['lastname'] . " " . $row['name'], $row['gender'],
        $row['dob'], $row['address'], $row['phone_student'], $row['skill'],
        $row['education_level'], $row['year']
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
$filename = 'students_register.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
