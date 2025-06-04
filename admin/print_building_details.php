<?php
session_start();
include '../conn_db.php';

// Check if the user is authenticated
if (!isset($_SESSION["admin_username"])) {
    header("Location: admin-login.php");
    exit();
}

// Get the building and room details from the URL parameters
$building_name = isset($_GET['building_name']) ? htmlspecialchars($_GET['building_name']) : '';
$roomNumber = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : 1;

// Fetch students from the register table for the given building and room
$sql_students = $conn->prepare("SELECT * FROM register WHERE building = ? AND room = ?");
$sql_students->bind_param("si", $building_name, $roomNumber);
$sql_students->execute();
$result_students = $sql_students->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Building Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-under {
            display: block;
            margin-left: auto;
            margin-right: 100px;
        }
        @media print {
            .no-print { display: none; }
        }
        body {
            margin: 10px;
            font-family: 'Khmer OS Siemreap';
        }
        h3 {
            font-family: 'Khmer OS Muol Light';
            font-size: 18px;
        }
        img {
            width: 150px;
        }
        .table td.text-center-vertical {
            text-align: center; 
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center">
        <h3>ព្រះរាជាណាចក្រកម្ពុជា</h3>
        <h3>ជាតិ​ សាសនា ព្រះមហាក្សត្រ</h3>
        <img src="../img/tc.png">
    </div>
    <h3>វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</h3>
    <h3>អន្តេវាសិកដ្ធាននិស្សិត</h3>
    <div class="table-responsive mt-5">
        <h3 style="text-align: center; margin-bottom: 20px;">បញ្ជីឈ្មោះនិសិ្សតស្នាក់នៅអន្តេវាសិកដ្ឋាន អគារ <?php echo htmlspecialchars($building_name); ?>, បន្ទប់លេខ​ <?php echo htmlspecialchars($roomNumber); ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ល.រ</th>
                    <th>បន្ទប់លេខ</th>
                    <th>ឈ្មោះនិស្សិត</th>
                    <th>ជំនាញ</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th>រាធានី​/ខេត្ត</th>
                    <th>ផ្សេងៗ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $count = 1;
            $previousRoom = ''; // To keep track of the previous room number
            $rowCount = 0; // To count the number of rows for a specific room

            // Collect all data to calculate rowspan
            $students = [];
            while ($row = $result_students->fetch_assoc()) {
                $students[$row['room']][] = $row;
            }

            foreach ($students as $room => $studentsList) {
                $rowSpan = count($studentsList); // Number of students in the same room

                foreach ($studentsList as $index => $student) {
                    echo "<tr>";
                    echo "<td>{$count}</td>";

                    // Only show room number once for the first student in the room
                    if ($index === 0) {
                        echo "<td rowspan='{$rowSpan}' class='text-center text-center-vertical'>" . htmlspecialchars($room) . "</td>";
                    }

                    echo "<td>" . htmlspecialchars($student["lastname"] . " " . $student["name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($student["skill"]) . "</td>";
                    echo "<td>" . htmlspecialchars($student["year"]) . "</td>";
                    echo "<td>" . htmlspecialchars($student["address"]) . "</td>";
                    echo "<td></td>";
                    echo "</tr>";

                    $count++;
                }
            }

            if ($count === 1) {
                echo "<tr><td colspan='7' class='text-center'>មិនមានទិន្នន័យនិស្សិត!</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="text-right">
        <p>ថ្ងៃ............ខែ.........ឆ្នាំ..........ព.ស..........</p>
        <p>ខេត្ត............ថ្ងៃទី........ខែ..........ឆ្នាំ.............</p>
        <b>ជ.ប្រធានគណៈកម្មការគ្រប់គ្រង់អន្តេវាសិកដ្ឋាន</b><br>
        <b class="center-under">អនុប្រអធាន</b>
    </div>
</div>
<script>
    window.onload = function() {
        window.print();
       window.onafterprint = function() {
            window.close();
        };
    };
</script>
</body>
</html>
