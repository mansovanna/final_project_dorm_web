<?php
session_start();
require_once('../conn_db.php');
include("../include/header.php"); 

// Fetch payment history for all students, including user_name
$query = "SELECT p.student_id, r.name, r.lastname, p.building, p.room_number, p.accommodation_fee, p.discount, p.water_fee, p.electricity_fee, p.total_fee, p.payment_date, p.status
          FROM payment p 
          INNER JOIN register r ON p.student_id = r.student_id
          WHERE p.status = 'Approved'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
    <div class="col-md-12">
        <div class="title mt-4 mb-3">
            <h3 style="font-weight: bold;  margin-bottom: 20px;">ការបង់ថ្លៃស្នាក់នៅ</h3>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="col-12 d-flex justify-content-end" style="margin-bottom: 15px;">
                    <a href="verify_payments.php" class="btn btn-primary">បង់ថ្លៃស្នាក់នៅ</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ល.រ</th>
                                <th>ID</th>
                                <th>ឈ្មោះនិស្សិត</th>
                                <th>អគារ</th>
                                <th>បន្ទប់</th>
                                <th>តម្លៃសរុប</th>
                                <th>ថ្ងៃ​/ខែ​/បង់ថ្លៃស្នាក់នៅ</th>
                                <th>ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php
                                $i = 1;
                                while($payment = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= htmlspecialchars($payment['student_id']); ?></td>
                                    <td><?= htmlspecialchars($payment['lastname'] . ' ' . $payment['name']); ?></td>
                                    <td><?= htmlspecialchars($payment['building']); ?></td>
                                    <td><?= htmlspecialchars($payment['room_number']); ?></td>
                                    <td><?= htmlspecialchars($payment['total_fee']); ?>៛</td>
                                    <td><?= htmlspecialchars($payment['payment_date']); ?></td>
                                    <td>
                                        <?php 
                                        if ($payment['status'] == 'Approved') {
                                            echo '<span style="background-color: #28a745; color: #fff; padding: 0.1em 0.5em; border-radius: 0.15em;">បង់ថ្លៃរួចរាល់</span>';
                                        } else {
                                            echo '<span style="background-color: #dc3545; color: #fff; padding: 0.5em 0.5em; border-radius: 0.15em;">មិនទាន់បង់</span>';
                                        }
                                        ?>
                                    </td>

                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">មិនមានប្រវត្តិការទូទាត់!td>
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
