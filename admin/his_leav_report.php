<?php
session_start();

// Include database connection
require_once '../conn_db.php';

$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$history = mysqli_query($conn, "SELECT * FROM reques_alaw WHERE student_id = '$student_id' AND status = 'អនុញ្ញាត'");

include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">ប្រវត្តិការសុំច្បាប់</h3>
    <div class="button mt-2 mr-2" style=" margin-bottom: 20px;">
        <a href="report_leave.php" class="btn btn-secondary"><i class="fas fa-share fa-flip-horizontal fa-fw"></i> ត្រឡប់</a>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">  
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead">
                        <tr>
                            <th>ល.រ</th>
                            <th>ID</th>
                            <th>ឈ្មោះនិស្សិត</th>
                            <th>ចំនួនថ្ងៃ</th>
                            <th>ចាប់ពីថ្ងៃទី</th>
                            <th>ដល់ថ្ងៃទី</th>
                            <th>មូលហេតុ</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(mysqli_num_rows($history) > 0): ?>
                        <?php
                        $i = 1;
                        while($result = mysqli_fetch_assoc($history)):
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $result['student_id']; ?></td>
                            <td><?= $result['user_name']; ?></td>
                            <td><?= $result['sumday']; ?></td>
                            <td><?= $result['first_date']; ?></td>
                            <td><?= $result['end_date']; ?></td>
                            <td><?= $result['reason']; ?></td>
                            <td>
                                <?php
                                if ($result["status"] == 'រង់ចាំ') {
                                    echo 'រង់ចាំការអនុញ្ញាត';
                                } elseif ($result["status"] == 'អនុញ្ញាត') {
                                    echo "បានអនុញ្ញាត";  
                                } elseif ($result["status"] == 'មិនអនុញ្ញាត') {
                                    echo "មិនអនុញ្ញាត";
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">មិនមានទិន្នន័យសុំច្បាប់!</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
