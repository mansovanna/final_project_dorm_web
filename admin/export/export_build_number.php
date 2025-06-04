<?php
session_start();
include '../../conn_db.php';

require '../../vendor/autoload.php'; // Ensure you have PhpSpreadsheet installed

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Fetch building name and room number
$building_name = isset($_GET['building_name']) ? htmlspecialchars($_GET['building_name']) : '';
$roomNumber = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : 1;

// Fetch students data
$sql_students = $conn->prepare("SELECT * FROM register WHERE building = ? AND room = ?");
$sql_students->bind_param("si", $building_name, $roomNumber);
$sql_students->execute();
$result_students = $sql_students->get_result();

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set the title at the top
$title = "និស្សិតស្នាក់នៅអគារ " . $building_name . ", បន្ទប់លេខ " . $roomNumber;
$sheet->setCellValue('A1', $title);
$sheet->mergeCells('A1:I1');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setName('Khmer OS Muol Light');

// Set headers
$headers = ['ល.រ', 'លេខសម្គាល់និស្សិត', 'ឈ្មោះនិស្សិត', 'ភេទ', 'ជំនាញ', 'កម្រិតសិក្សា', 'ឆ្នាំសិក្សា', 'បន្ទប់លេខ', 'លេខទូរស័ព្ទនិស្សិត'];
$sheet->fromArray($headers, NULL, 'A2');

// Add data
$rowNumber = 3; // Starting row for data
$count = 1;

if ($result_students->num_rows > 0) {
    while ($row = $result_students->fetch_assoc()) {
        $studentData = [
            $count++, // Row count
            htmlspecialchars($row['student_id']),
            htmlspecialchars($row['lastname'] . " " . $row['name']),
            htmlspecialchars($row['gender']),
            htmlspecialchars($row['skill']),
            htmlspecialchars($row['education_level']),
            htmlspecialchars($row['year']),
            htmlspecialchars($row['room']),
            htmlspecialchars($row['phone_student']) // Phone number
        ];

        $sheet->fromArray($studentData, NULL, 'A' . $rowNumber++);

        // Format the phone number as text to avoid scientific notation
        $sheet->getStyle('I' . ($rowNumber - 1))
              ->getNumberFormat()
              ->setFormatCode(NumberFormat::FORMAT_TEXT);

        $sheet->getStyle('A' . ($rowNumber - 1) . ':I' . ($rowNumber - 1))
        ->getAlignment()
        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
}

// Apply border to all cells with data
$cellRange = 'A2:I' . ($rowNumber - 1); // From headers to last data row
$sheet->getStyle($cellRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

$sheet->getStyle($cellRange)->getFont()->setName('Khmer OS Siemreap');
// Auto-size columns to fit content
foreach (range('A', 'I') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Set download headers for Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="building_details.xlsx"');
header('Cache-Control: max-age=0');

// Write the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

?>
