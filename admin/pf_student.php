<?php
session_start();

// Include database connection
require_once '../conn_db.php';

// Check if student_id is provided in the URL
if (!isset($_GET['student_id'])) {
    header("Location: rg_admin.php");
    exit();
}

// Retrieve student_id from the URL and validate it
$student_id = intval($_GET['student_id']);

// Prepare and execute the query to fetch student information
$query = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if student exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "Student not found.";
    exit();
}

$_SESSION['user'] = $user;

// Process password update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo "<script>alert('ពាក្យសម្ងាត់ថ្មី និងការបញ្ជាក់មិនដូចគ្នា។ សូមព្យាយាមម្ដងទៀត');</script>";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password in the database
        $update_query = "UPDATE register SET password = ? WHERE student_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $hashed_password, $student_id);

        if ($update_stmt->execute()) {
            echo "<script>alert('ពាក្យសម្ងាត់ត្រូវបានប្តូរដោយជោគជ័យ!');</script>";
        } else {
            echo "<script>alert('មានបញ្ហាក្នុងការប្តូរពាក្យសម្ងាត់។');</script>";
        }
    }
}


// Include header
include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Information</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
    <h3 style="margin-bottom: 30px;">ព័ត៌មានផ្ទាល់ខ្លួននិស្សិត</h3>
    <div class="row">
        <div class="col-md-12 text-start">
            <a href="all_user.php" class="btn btn-secondary me-2"><i class="fas fa-share fa-flip-horizontal fa-fw"></i>
                ត្រឡប់
            </a>
            <?php if ($_SESSION['admin_username'] == 'admin'): ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updatePasswordModal">
                <i class="fa-solid fa-lock"></i> ប្ដូរលេខសម្ងាត់
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card card-primary card-outline mt-3">
        <div class="card-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="studentTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                       aria-controls="profile" aria-selected="true">ប្រវត្តិរូប</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="register-history-tab" data-toggle="tab" href="#register-history"
                       role="tab" aria-controls="register-history" aria-selected="false">ប្រវត្តិស្នាក់នៅ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="leave-history-tab" data-toggle="tab" href="#leave-history"
                       role="tab" aria-controls="leave-history" aria-selected="false">ប្រវត្តិសុំច្បាប់</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="payment-history-tab" data-toggle="tab" href="#payment-history"
                       role="tab" aria-controls="payment-history" aria-selected="false">ការបង់ថ្លៃស្នាក់នៅ</a>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="studentTabContent">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row mt-3">
                        <div class="col-md-4 text-center">
                            <img src="<?php echo !empty($user['img']) ? htmlspecialchars('../' . $user['img']) : '../img/user1.png'; ?>" class="img-fluid"
                                style="width: 200px; height: 240px; object-fit: cover; border-radius: 50%; border: 2.5px solid #3572EF; margin-bottom: 10px;">
                            <!-- <?php if ($_SESSION['admin_username'] == 'admin'): ?>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updatePasswordModal">កែប្រែលេខសំងាត់</button>
                                </div>
                            <?php endif; ?> -->
                        </div>
                        <div class="col-md-8 mt-3">
                            <table class="table table-striped">
                                <tr>
                                    <th colspan="2">ព័ត៌មានលម្អិត:</th>
                                </tr>
                                <tr>
                                    <th>លេខសម្គាល់និស្សិត</th>
                                    <td><?= htmlspecialchars($user['student_id']); ?></td>
                                </tr>
                                <tr>
                                    <th>នាមត្រកូល នាមខ្លួន</th>
                                    <td><?= htmlspecialchars($user['lastname'] . ' ' . $user['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>អក្សរឡាតាំង</th>
                                    <td><?= htmlspecialchars($user['username']); ?></td>
                                </tr>
                                <tr>
                                    <th>ភេទ</th>
                                    <td><?= htmlspecialchars($user['gender']); ?></td>
                                </tr>
                                <tr>
                                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                                    <td><?= htmlspecialchars($user['dob']); ?></td>
                                </tr>
                                <tr>
                                    <th>អាសយដ្ឋាន</th>
                                    <td><?= htmlspecialchars($user['address']); ?></td>
                                </tr>
                                <tr>
                                    <th>លេខទូរស័ព្ទនិស្សិត</th>
                                    <td><?= htmlspecialchars($user['phone_student']); ?></td>
                                </tr>
                                <tr>
                                    <th>លេខទូរស័ព្ទអាណាព្យាបាល</th>
                                    <td><?= htmlspecialchars($user['phone_parent']); ?></td>
                                </tr>
                                <tr>
                                    <th>ជំនាញ</th>
                                    <td><?= htmlspecialchars($user['skill']); ?></td>
                                </tr>
                                <tr>
                                    <th>កម្រិតសិក្សា</th>
                                    <td><?= htmlspecialchars($user['education_level']); ?></td>
                                </tr>
                                <tr>
                                    <th>ឆ្នាំសិក្សា</th>
                                    <td><?= htmlspecialchars($user['year']); ?></td>
                                </tr>
                                <tr>
                                    <th>ថ្ងៃខែឆ្នាំចូលស្នាក់នៅ</th>
                                    <td><?= htmlspecialchars($user['stay']); ?></td>
                                </tr>
                                <tr>
                                    <th>អគារស្នាក់នៅ</th>
                                    <td><?= htmlspecialchars($user['building']); ?></td>
                                </tr>
                                <tr>
                                    <th>បន្ទប់លេខ</th>
                                    <td><?= htmlspecialchars($user['room']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Register History Tab -->
                <div class="tab-pane fade" id="register-history" role="tabpanel" aria-labelledby="register-history-tab">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php
                            // Prepare and execute the query for Register data
                            $register_query = "SELECT * FROM register WHERE student_id = ?";
                            $stmt_register = $conn->prepare($register_query);
                            $stmt_register->bind_param("i", $student_id);
                            $stmt_register->execute();
                            $result_register = $stmt_register->get_result();

                            // Prepare and execute the query for History data
                            $history_query = "SELECT * FROM history WHERE student_id = ?";
                            $stmt_history = $conn->prepare($history_query);
                            $stmt_history->bind_param("i", $student_id);
                            $stmt_history->execute();
                            $result_history = $stmt_history->get_result();
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ល.រ</th>
                                            <th>លេខសម្គាល់និស្សិត</th>
                                            <th>ឈ្មោះនិស្សិត</th>
                                            <th>ជំនាញ</th>
                                            <th>កម្រិតសិក្សា</th>
                                            <th>ឆ្នាំ</th>
                                            <th>ថ្ងៃចូលស្នាក់នៅ</th>
                                            <th>អគារ</th>
                                            <th>លេខបន្ទប់</th>
                                            <th>លេខទូរស័ព្ទ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Initialize the counter
                                        $i = 1;

                                        // Display data from the 'register' table
                                        if (mysqli_num_rows($result_register) > 0) {
                                            while ($row = mysqli_fetch_assoc($result_register)) {
                                                ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= htmlspecialchars($row['student_id']); ?></td>
                                                    <td><?= htmlspecialchars($row['lastname'] . ' ' . $row['name']); ?></td>
                                                    <td><?= htmlspecialchars($row['skill']); ?></td>
                                                    <td><?= htmlspecialchars($row['education_level']); ?></td>
                                                    <td><?= htmlspecialchars($row['year']); ?></td>
                                                    <td><?= htmlspecialchars($row['stay']); ?></td>
                                                    <td><?= htmlspecialchars($row['building']); ?></td>
                                                    <td><?= htmlspecialchars($row['room']); ?></td>
                                                    <td><?= htmlspecialchars($row['phone_student']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                        // Display data from the 'history' table
                                        if (mysqli_num_rows($result_history) > 0) {
                                            while ($row = mysqli_fetch_assoc($result_history)) {
                                                ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= htmlspecialchars($row['student_id']); ?></td>
                                                    <td><?= htmlspecialchars($row['lastname'] . ' ' . $row['name']); ?></td>
                                                    <td><?= htmlspecialchars($row['skill']); ?></td>
                                                    <td><?= htmlspecialchars($row['education_level']); ?></td>
                                                    <td><?= htmlspecialchars($row['year']); ?></td>
                                                    <td><?= htmlspecialchars($row['change_date']); ?></td>
                                                    <td><?= htmlspecialchars($row['building']); ?></td>
                                                    <td><?= htmlspecialchars($row['room']); ?></td>
                                                    <td><?= htmlspecialchars($row['phone_student']); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                        // Show a message if no data is found in both tables
                                        if (mysqli_num_rows($result_register) == 0 && mysqli_num_rows($result_history) == 0) {
                                            ?>
                                            <tr>
                                                <td colspan="11" class="text-center">No register history found.</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                 </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leave History Tab -->
                <div class="tab-pane fade" id="leave-history" role="tabpanel" aria-labelledby="leave-history-tab">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php
                            // Prepare and execute the query for Leave History
                            $leave_query = "SELECT * FROM reques_alaw WHERE student_id = ?";
                            $stmt_leave = $conn->prepare($leave_query);
                            $stmt_leave->bind_param("i", $student_id);
                            $stmt_leave->execute();
                            $result_leave = $stmt_leave->get_result();
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ល.រ</th>
                                        <th>លេខសម្គាល់និស្សិត</th>
                                        <th>ឈ្មោះនិស្សិត</th>
                                        <th>ចំនួនថ្ងៃ</th>
                                        <th>ចាប់ពីថ្ងៃទី</th>
                                        <th>ដល់ថ្ងៃទី</th>
                                        <th>មូលហេតុ</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if ($result_leave->num_rows > 0) {
                                        $i = 1;
                                        while ($result = $result_leave->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= htmlspecialchars($result['student_id']); ?></td>
                                                <td><?= htmlspecialchars($result['user_name']); ?></td>
                                                <td><?= htmlspecialchars($result['sumday']); ?></td>
                                                <td><?= htmlspecialchars($result['first_date']); ?></td>
                                                <td><?= htmlspecialchars($result['end_date']); ?></td>
                                                <td><?= htmlspecialchars($result['reason']); ?></td>
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
                                            <?php
                                        endwhile;
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="8" class="text-center">មិនមានប្រវត្តិការសុំច្បាប់!</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment History Tab -->
                <div class="tab-pane fade" id="payment-history" role="tabpanel" aria-labelledby="payment-history-tab">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <?php
                            // Prepare and execute the query for Payment History
                            $payment_query = "SELECT p.student_id, r.name, r.lastname, p.building, p.room_number, p.accommodation_fee, p.discount, p.water_fee, p.electricity_fee, p.total_fee, p.payment_date 
                                              FROM payment p 
                                              INNER JOIN register r ON p.student_id = r.student_id 
                                              WHERE p.student_id = ?";
                            $stmt_payment = $conn->prepare($payment_query);
                            $stmt_payment->bind_param("i", $student_id);
                            $stmt_payment->execute();
                            $result_payment = $stmt_payment->get_result();
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ល.រ</th>
                                        <th>លេខសម្គាល់និស្សិត</th>
                                        <th>ឈ្មោះនិស្សិត</th>
                                        <th>អគារ</th>
                                        <th>បន្ទប់</th>
                                        <th>ថ្លៃស្នាក់នៅ</th>
                                        <th>បញ្ចុះតម្លៃ</th>
                                        <th>ថ្លៃទឺក</th>
                                        <th>ថ្លៃភ្លើង</th>
                                        <th>តម្លៃសរុប</th>
                                        <th>ថ្ងៃ​/ខែ​/បង់ថ្លៃស្នាក់នៅ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($result_payment->num_rows > 0): ?>
                                        <?php
                                        $i = 1;
                                        while ($payment = $result_payment->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= htmlspecialchars($payment['student_id']); ?></td>
                                                <td><?= htmlspecialchars($payment['lastname'] . ' ' . $payment['name']); ?></td>
                                                <td><?= htmlspecialchars($payment['building']); ?></td>
                                                <td><?= htmlspecialchars($payment['room_number']); ?></td>
                                                <td><?= htmlspecialchars(number_format($payment['accommodation_fee'], 2)); ?>៛</td>
                                                <td><?= htmlspecialchars(number_format($payment['discount'], 2)); ?>៛</td>
                                                <td><?= htmlspecialchars(number_format($payment['water_fee'], 2)); ?>៛</td>
                                                <td><?= htmlspecialchars(number_format($payment['electricity_fee'], 2)); ?>៛</td>
                                                <td><?= htmlspecialchars(number_format($payment['total_fee'], 2)); ?>៛</td>
                                                <td><?= htmlspecialchars($payment['payment_date']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="text-center">មិនមានប្រវត្តិការទូទាត់!</td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div> <!-- End of Tab Content -->
        </div>
    </div>
</div>


<!-- Modal for Updating Password -->
<div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePasswordModalLabel">កែប្រែកូដសំងាត់</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="passwordUpdateForm">
                    <div class="form-group">
                        <label for="password">កូដសំងាត់ថ្មី</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePasswordVisibility('password')">
                                    <i id="eye-icon-password" class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">បញ្ជាក់កូដសំងាត់ថ្មី</label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePasswordVisibility('confirm_password')">
                                    <i id="eye-icon-confirm_password" class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">បោះបង់</button>
                        <button type="submit" name="update_password" class="btn btn-primary">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    var input = document.getElementById(inputId);
    var icon = document.getElementById("eye-icon-" + inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

<!-- Bootstrap and jQuery Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdOUpR0AdOYLYKxtVULiw0Ij6hRx6Kk8pbf0Xp/VuNEu6jms9E6dNopddwJIVw"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"
        integrity="sha384-pTT6JIlXt7Kj50Fh4oNJryGAYWHbHgKxIGklWKnceVEJYB1VQVlv3vbZwXTEBcBx"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgQ2L6d4uFKrMlywJVfN9z4eKQv0BbQ7rJXzR4yg5LL8RmFuwUs"
        crossorigin="anonymous"></script>
</body>
</html>


