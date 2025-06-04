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
$staff_Name = isset($_GET['staff_Name']) ? $conn->real_escape_string($_GET['staff_Name']) : '';
$phone_number = isset($_GET['phone_number']) ? $conn->real_escape_string($_GET['phone_number']) : '';

$sql_select = "SELECT * FROM staff WHERE staff_Name LIKE '%$staff_Name%' AND phone_number LIKE '%$phone_number%' AND id != 81 ORDER BY id DESC";

// Execute query
$result = $conn->query($sql_select);

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set title
$title = "បញ្ជីឈ្មោះបុគ្គលិកគ្រប់គ្រងអន្តេវាសិកដ្ឋាន"; // Title text
$sheet->mergeCells('A1:E1');
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
$sheet->getStyle('A1:E1')->applyFromArray($titleStyle);

// Set column headers
$headers = [
    'ល.រ', 'ឈ្មោះបុគ្គលិក', 'អក្សរឡាតាំង', 'លេខទូរស័ព្ទ', 'សារអេឡិចត្រូនិក'
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
$sheet->getStyle('A2:E2')->applyFromArray($headerStyle);

// Set table data
$rowIndex = 3;
$i = 1;
while ($row = $result->fetch_assoc()) {
    $data = [
        $i++, $row['staff_Name'], $row['username'], $row['phone_number'],
        $row['Email']
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
        'name' => 'Khmer OS Siemreap', // Specify your desired font family
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
    ],
];
$sheet->getStyle('A3:E' . ($rowIndex - 1))->applyFromArray($recordStyle);

// Output to Excel
$writer = new Xlsx($spreadsheet);
$filename = 'staff.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
