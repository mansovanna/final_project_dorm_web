<?php
session_start();
include '../../conn_db.php';

// Initialize search variables
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Construct the SQL query with search filters
$sql = "SELECT p.student_id, r.name, r.lastname, p.building, p.room_number, 
               p.accommodation_fee, p.discount, p.water_fee, 
               p.electricity_fee, p.total_fee, p.payment_date, p.status
          FROM payment p 
          INNER JOIN register r ON p.student_id = r.student_id
          WHERE 1=1";

$params = [];
$types = '';

// Append conditions to the query
if ($student_id) {
    $sql .= " AND p.student_id = ?";
    $params[] = $student_id;
    $types .= 's';
}
if ($skill) {
    $sql .= " AND r.skill = ?";
    $params[] = $skill;
    $types .= 's';
}
if ($year) {
    $sql .= " AND r.year = ?";
    $params[] = $year;
    $types .= 's';
}

// Prepare statement
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error executing query: " . $stmt->error);
}

// Close the connection
$stmt->close();
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
    <h3 class="mt-3 text-center" style="font-weight: bold;"> របាយការណ៏និស្សិតបង់ថ្លៃស្នាក់នៅ</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead">
                <tr>
                    <th>ល.រ</th>
                    <th>លេខសម្គាល់និស្សិត</th>
                    <th>ឈ្មោះនិស្សិត</th>
                    <th>អគារ</th>
                    <th>បន្ទប់</th>  
                    <th>តម្លៃសរុប</th>
                    <th>ថ្ងៃ​/ខែ​/បង់ថ្លៃស្នាក់នៅ</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($row['student_id']); ?></td>
                <td><?= htmlspecialchars($row['lastname'] . ' ' . $row['name']); ?></td>
                <td><?= htmlspecialchars($row['building']); ?></td>
                <td><?= htmlspecialchars($row['room_number']); ?></td>
                <td><?= htmlspecialchars($row['total_fee']); ?>៛</td>
                <td><?= htmlspecialchars($row['payment_date']); ?></td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr><td colspan="7">មិនមានរបាយការណ៏និស្សិតសុំច្បាប់</td></tr>
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
