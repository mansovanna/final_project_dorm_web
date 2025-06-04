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
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Initialize SQL query
$sql = "SELECT p.student_id, r.name, r.lastname, p.building, p.room_number, p.accommodation_fee, p.discount, p.water_fee, p.electricity_fee, p.total_fee, p.payment_date, p.status
          FROM payment p 
          INNER JOIN register r ON p.student_id = r.student_id
          WHERE 1=1";

// Build the SQL query with search parameters
$params = [];
$types = '';

if ($student_id) {
    $sql .= " AND p.student_id = ?";
    $types .= 's';
    $params[] = $student_id;
}
if ($skill) {
    $sql .= " AND r.skill = ?";
    $types .= 's';
    $params[] = $skill;
}
if ($year) {
    $sql .= " AND r.year = ?";
    $types .= 's';
    $params[] = $year;
}

$sql .= " GROUP BY p.student_id"; // Use p instead of lr

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params); // Binding parameters
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$title = "បញ្ជីឈ្មោះនិស្សិតបង់ថ្លៃ"; // Title text
$sheet->mergeCells('A1:G1');
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
$sheet->getStyle('A1:G1')->applyFromArray($titleStyle);

// Set column headers
$headers = [
    'ល.រ', 'លេខសម្គាល់និស្សិត', 'ឈ្មោះនិស្សិត', 'អគារ', 'បន្ទប់', 'តម្លៃសរុប', 'ថ្ងៃ​/ខែ​/បង់ថ្លៃស្នាក់នៅ'
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
$sheet->getStyle('A2:G2')->applyFromArray($headerStyle);

// Set table data
$rowIndex = 3;
$i = 1;
while ($row = $result->fetch_assoc()) {
    $data = [
        $i++, 
        $row['student_id'], 
        $row['lastname'] . " " . $row['name'], 
        $row['building'], 
        $row['room_number'], 
        $row['total_fee'], 
        $row['payment_date'] // Corrected this line to match the existing column names
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
$sheet->getStyle('A2:G' . ($rowIndex - 1))->applyFromArray($styleArray);

// Set column widths
foreach (range('A', 'G') as $columnID) {
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
$sheet->getStyle('A3:G' . ($rowIndex - 1))->applyFromArray($recordStyle);

// Output to Excel
$writer = new Xlsx($spreadsheet);
$filename = 'students_pay.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
$conn->close(); // Close the database connection
exit;
