<?php
session_start();
include '../../conn_db.php';

// Initialize search variables
$first_date = isset($_GET['first_date']) ? $_GET['first_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : ''; 
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';

// Construct the SQL query with search filters
$sql = "SELECT lr.student_id, lr.user_name, r.skill,
                MIN(lr.first_date) as first_date, 
                MAX(lr.end_date) as end_date, 
                GROUP_CONCAT(lr.reason SEPARATOR ', ') as reason, 
                COUNT(lr.reason) as leave_count, 
                lr.status 
        FROM reques_alaw lr
        JOIN register r ON lr.student_id = r.student_id
        WHERE lr.status = 'អនុញ្ញាត'";

// Append conditions to the query
if ($first_date) {
    $first_date = date('Y-m-d', strtotime($first_date)); // Normalize date format
    $sql .= " AND lr.first_date >= '" . $conn->real_escape_string($first_date) . " 00:00:00'";
}
if ($end_date) {
    $end_date = date('Y-m-d', strtotime($end_date)); // Normalize date format
    $sql .= " AND lr.end_date <= '" . $conn->real_escape_string($end_date) . " 23:59:59'";
}
if ($student_id) {
    $sql .= " AND lr.student_id LIKE '%" . $conn->real_escape_string($student_id) . "%'";
}
if ($skill) { // Add skill filter to the query
    $sql .= " AND r.skill LIKE '%" . $conn->real_escape_string($skill) . "%'";
}

$sql .= " GROUP BY lr.student_id";

$result = $conn->query($sql);

if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Report</title>
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
        <img src="../../img/tc.png" alt="Logo">
    </div>
    <h3 class="mt-3 text-center" style="font-weight: bold;"> របាយការណ៏និស្សិតសុំច្បាប់</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead">
                <tr>
                    <th>ល.រ</th>
                    <th>លេខសម្គាល់និស្សិត</th>
                    <th>ឈ្មោះនិស្សិត</th>
                    <th>ជំនាញ</th>
                    <th>ចំនួនការសុំច្បាប់</th>  
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo htmlspecialchars($row["student_id"]); ?></td>
                <td><?php echo htmlspecialchars($row["user_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["skill"]); ?></td>
                <td><?php echo htmlspecialchars($row["leave_count"]); ?></td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr><td colspan="5">មិនមានរបាយការណ៏និស្សិតសុំច្បាប់</td></tr>
            <?php
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
    };
    window.onafterprint = function() {
        window.close();
    };
</script>

</body>
</html>
