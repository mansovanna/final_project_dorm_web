<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
require_once('conn_db.php');
include('include/header_student.php');

// Fetch leave history for the current user
$history = mysqli_query($conn, "SELECT * FROM reques_alaw WHERE student_id = '{$_SESSION['user_id']}'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/table.css">
</head>
<body>
    <div class="container">
        <div class="title mt-4 mb-3 text-left">
            <h3 style=" font-weight: bold;">ប្រវត្តិការសុំច្បាប់</h3>
        </div>
        <?php
        $sql = "SELECT * FROM register WHERE status = 'អនុញ្ញាត' AND student_id = '{$_SESSION['user_id']}'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0): ?>
            <div class="col-12 d-flex justify-content-end">
                <a href="leave.php" class="btn btn-success">ស្នើសុំច្បាប់</a>
            </div>
        <?php endif; ?>

        <div class="table-responsive p-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width:6%" class="text-center">ល.រ</th>
                        <th style="width:10%" class="text-center">ID</th>
                        <th style="width:12%" class="text-center">ឈ្មោះនិស្សិត</th>
                        <th style="width:12%" class="text-center">ចំនួនថ្ងៃ</th>
                        <th style="width:12%" class="text-center">ចាប់ពីថ្ងៃទី</th>
                        <th style="width:12%" class="text-center">ដល់ថ្ងៃទី</th>
                        <th style="width:14%" class="text-center">មូលហេតុ</th>
                        <th style="width:12%" class="text-center">សកម្មភាព</th>
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
                            <td class="text-center">
                                <?php 
                                if ($result["status"] == 'រង់ចាំ') {
                                    echo '<span class="badge-warning" style="color: #fff; padding: 0.1em 0.5em; border-radius: 0.15em;">រង់ចាំការអនុញ្ញាត</span>';
                                } elseif ($result["status"] == 'អនុញ្ញាត') {
                                    echo '<span style="background-color: #28a745; color: #fff; padding: 0.1em 0.5em; border-radius: 0.15em;">បានអនុញ្ញាត</span>';  
                                } elseif ($result["status"] == 'មិនអនុញ្ញាត') {
                                    echo '<span class="badge-danger" style="color: #fff; padding: 0.1em 0.5em; border-radius: 0.15em;">មិនអនុញ្ញាត</span>'; 
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">មិនមានប្រវត្តិការសុំច្បាប់!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
</body>
</html>
