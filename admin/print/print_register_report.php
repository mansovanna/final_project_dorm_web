<?php
session_start();
include '../../conn_db.php';

// Initialize search variables
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Construct the SQL query with search filters
$sql_select = "SELECT * FROM register WHERE status = 'អនុញ្ញាត'";
$conditions = [];
$params = [];

if (!empty($skill)) {
    $conditions[] = "skill LIKE ?";
    $params[] = "%$skill%";
}
if (!empty($student_id)) {
    $conditions[] = "student_id LIKE ?";
    $params[] = "%$student_id%";
}
if (!empty($year)) {
    $conditions[] = "year LIKE ?";
    $params[] = "%$year%";
}

if (count($conditions) > 0) {
    $sql_select .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql_select);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

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
        <img src="../../img/tc.png">
    </div>
    <h3 class="mt-3 text-center" style="font-weight: bold;"> របាយការណ៏និស្សិតស្នាក់នៅអន្តេវាសិកដ្ឋាន</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead">
                <tr>
                    <th>ល.រ</th>
                    <th>លេខសម្គាល់និស្សិត</th>
                    <th>ឈ្មោះនិស្សិត</th>
                    <th>អគារស្នាក់នៅ</th>
                    <th>បន្ទប់លេខ</th>
                    <th>ថ្ងៃចូលស្នាក់នៅ</th>
                    <th>លេខទូរសព្ទ័និស្សិត</th>  
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
                <td><?php echo ($row["student_id"]); ?></td>
                <td><?php echo $row["lastname"] . " " . $row["name"]; ?></td>
                <td><?php echo ($row["building"]); ?></td>
                <td><?php echo ($row["room"]); ?></td>
                <td><?php echo ($row["stay"]); ?></td>
                <td><?php echo ($row["phone_student"]); ?></td>
            </tr>
            <?php
                }
            } else {
            ?>
            <tr><td colspan="8">មិនមានរបាយការណ៏និស្សិតស្នាក់នៅ</td></tr>
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
