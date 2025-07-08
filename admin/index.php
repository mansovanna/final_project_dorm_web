<?php
session_start();

// Include database connection
include '../conn_db.php';

// Check if the user is not authenticated
if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch total number of entries from 'register' table
$sql_total_register = "SELECT COUNT(CASE WHEN status = 'រង់ចាំ' THEN 1 END) AS total FROM register";
$result_total_register = $conn->query($sql_total_register);
if ($result_total_register->num_rows > 0) {
    $row_total_register = $result_total_register->fetch_assoc();
    $total_entries_register = $row_total_register["total"];
} else {
    $total_entries_register = 0;
}

// Fetch total number of entries from 'reques_alaw' table
$sql_total_reques_alaw = "SELECT COUNT(CASE WHEN status = 'រង់ចាំ' THEN 1 END) AS total FROM reques_alaw";
$result_total_reques_alaw = $conn->query($sql_total_reques_alaw);
if ($result_total_register->num_rows > 0) {
    $row_total_reques_alaw = $result_total_reques_alaw->fetch_assoc();
    $total_entries_reques_alaw = $row_total_reques_alaw["total"];
} else {
    $total_entries_register = 0;
}

// Fetch total number of students with status 'អនុញ្ញាត' not listed in the payment table
$sql_total_allowed_not_paid = "
    SELECT COUNT(*) AS total
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";
$result_total_allowed_not_paid = $conn->query($sql_total_allowed_not_paid);
if ($result_total_allowed_not_paid->num_rows > 0) {
    $row_total_allowed_not_paid = $result_total_allowed_not_paid->fetch_assoc();
    $total_entries_allowed_not_paid = $row_total_allowed_not_paid["total"];
} else {
    $total_entries_allowed_not_paid = 0;
}

// Fetch pending registrations
$sql_pending_students = "SELECT * FROM register WHERE status = 'រង់ចាំ'";
$result_pending_students = $conn->query($sql_pending_students);

// Fetch pending leave request
$sql_pending_leave = "SELECT * FROM reques_alaw WHERE status = 'រង់ចាំ'";
$result_pending_leave = $conn->query($sql_pending_leave);

$select_total = "SELECT status FROM register WHERE status = 'អនុញ្ញាត'";
$check_res_total = $conn->query($select_total);

$i = 0; // Initialize count outside the loop

if ($check_res_total->num_rows > 0) {
    while ($row_total = $check_res_total->fetch_assoc()) {
        // Increment count if status matches
        $i++;
    }
}

// select payment table status is Pending

function getPaymentCount($conn) {
    $query = "SELECT COUNT(*) AS total FROM payment WHERE status = 'Pending'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}

// Fetch the total number of entries in the 'register' table
$getPaymentCount = getPaymentCount($conn);
// Function to get the total number of entries in the 'register' table
function getTotalEntries($conn) {
    $query = "SELECT COUNT(*) AS total FROM register";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    return 0;
}


// Get the current year

// Get the current year
$currentYear = date('Y');

// Create dynamic WHERE clause
$where = "WHERE YEAR(s.stay) <= $currentYear AND s.status NOT IN ('Pending')";

// Call function and get result
$studentPaymentStatus = getStudentPaymentStatusPerYear($conn, $currentYear, $where);

// Function definition
function getStudentPaymentStatusPerYear($conn, $currentYear, $where) {
    $query = "SELECT s.student_id, s.name, s.lastname, s.skill, YEAR(s.stay) AS start_year, s.gender, s.dob, s.address, s.phone_student, s.education_level, s.status
              FROM register s 
              $where
              ORDER BY s.student_id ASC";
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . mysqli_error($conn),
            'total' => 0
        ];
    }

    $rows = [];
    $total = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $studentId = $row['student_id'];
        $fromYear = $row['start_year'];
        $toYear = $currentYear;

        // Get approved payment years
        $paymentQuery = "SELECT YEAR(payment_date) AS paid_year 
                         FROM payment 
                         WHERE student_id = '$studentId' AND status = 'Approved'";
        $paymentResult = mysqli_query($conn, $paymentQuery);

        $paidYears = [];
        while ($payRow = mysqli_fetch_assoc($paymentResult)) {
            $paidYears[] = $payRow['paid_year'];
        }

        // Check for unpaid years
        $notPaidYears = [];
        for ($year = $fromYear; $year <= $toYear; $year++) {
            if (!in_array($year, $paidYears)) {
                $notPaidYears[] = $year;
            }
        }

        // Only include students with at least one unpaid year
        if (!empty($notPaidYears)) {
            foreach ($notPaidYears as $year) {
                $rows[] = [
                    'student_id' => $studentId,
                    'name' => $row['name'],
                    'lastname' => $row['lastname'],
                    'skill' => $row['skill'],
                    'year' => $year,
                    'message' => 'មិនទាន់បានបង់ប្រាក់',
                    'gender' => $row['gender'],
                    'dob' => $row['dob'],
                    'address' => $row['address'],
                    'phone_student' => $row['phone_student'],
                    'education_level' => $row['education_level'],
                    'status' => 'Not Paid'
                ];
                $total++;
            }
        }
    }

    return [
        'status' => 'success',
        'data' => $rows,
        'total' => $total
    ];
}

// Close connection
$conn->close();
?>
<?php 
include("../include/header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/header.css">
    <style>
        .dropdown-menu {
            width: 300px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .dropdown-header {
            text-align: center;
        }

        .dropdown-header img {
            width: 80px;
            height: 90px;
            margin-bottom: 10px;
        }

        .dropdown-header h5 {
            margin-bottom: 5px;
        }

        .dropdown-header p {
            margin-bottom: 20px;
            color: #666;
        }

        .dropdown-item {
            text-align: center;
        }

        .hero_box_content {
            padding: 8px 10px;
            background: #5fa6d3;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <div class="mb-3">
            <h2>ផ្ទាំងបង្ហាញព៍ត៌មាន</h2>
        </div>
        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <!-- <a href="all_user.php" class="text-decoration-none"> -->
                <div class="card btn-shadow bg-info text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="numbers display-4"><?php echo $i; ?></div>
                            <div class="cardName">និស្សិតស្នាក់នៅ</div>
                        </div>
                        <div class="iconBx">
                            <i class="fas fa-user-friends fa-2x"></i>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
            </div>

            <div class="col-md-3 mb-3">
                <!-- <a href="rg_admin.php" class="text-decoration-none"> -->
                <div class="card btn-shadow bg-primary text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="numbers display-4"><?php echo $total_entries_register; ?></div>
                            <div class="cardName">និស្សិតស្នើសុំស្នាក់នៅ</div>
                        </div>
                        <div class="iconBx">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
            </div>

            <div class="col-md-3 mb-3">
                <!-- <a href="leave_ad.php" class="text-decoration-none"> -->
                <div class="card btn-shadow bg-success text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="numbers display-4"><?php echo $total_entries_reques_alaw; ?></div>
                            <div class="cardName">និស្សិតស្នើសុំច្បាប់</div>
                        </div>
                        <div class="iconBx">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
            </div>

            <div class="col-md-3 mb-3">
                <!-- <a href="no_pay.php" class="text-decoration-none"> -->
                <div class="card btn-shadow bg-danger text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="numbers display-4"><?php echo $studentPaymentStatus['total']; ?></div>
                            <div class="cardName">និស្សិតមិនទាន់បង់ប្រាក់</div>
                        </div>
                        <div class="iconBx">
                            <i class="fa-solid fa-sack-xmark fa-2x"></i>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
            </div>


            <!-- Studet Request Payment -->
            <div class="col-md-3 mb-3">
                <!-- <a href="no_pay.php" class="text-decoration-none"> -->
                <div class="card btn-shadow bg-warning text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="numbers display-4"><?php echo $getPaymentCount; ?></div>
                            <div class="cardName">និស្សិតកំពុងបង់ប្រាក់</div>
                        </div>
                        <div class="iconBx">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40"
                               fill="none">
                                <path
                                    d="M20.016 2C18.9026 2 18 4.68629 18 8H20.016C20.9876 8 21.4734 8 21.7741 7.66455C22.0749 7.32909 22.0225 6.88733 21.9178 6.00381C21.6414 3.67143 20.8943 2 20.016 2Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path
                                    d="M18 8.05426V18.6458C18 20.1575 18 20.9133 17.538 21.2108C16.7831 21.6971 15.6161 20.6774 15.0291 20.3073C14.5441 20.0014 14.3017 19.8485 14.0325 19.8397C13.7417 19.8301 13.4949 19.9768 12.9709 20.3073L11.06 21.5124C10.5445 21.8374 10.2868 22 10 22C9.71321 22 9.45546 21.8374 8.94 21.5124L7.02913 20.3073C6.54415 20.0014 6.30166 19.8485 6.03253 19.8397C5.74172 19.8301 5.49493 19.9768 4.97087 20.3073C4.38395 20.6774 3.21687 21.6971 2.46195 21.2108C2 20.9133 2 20.1575 2 18.6458V8.05426C2 5.20025 2 3.77325 2.87868 2.88663C3.75736 2 5.17157 2 8 2H20"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6 6H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M8 10H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M12.5 10.875C11.6716 10.875 11 11.4626 11 12.1875C11 12.9124 11.6716 13.5 12.5 13.5C13.3284 13.5 14 14.0876 14 14.8125C14 15.5374 13.3284 16.125 12.5 16.125M12.5 10.875C13.1531 10.875 13.7087 11.2402 13.9146 11.75M12.5 10.875V10M12.5 16.125C11.8469 16.125 11.2913 15.7598 11.0854 15.25M12.5 16.125V17"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- </a> -->
            </div>
            <!-- End Studet Request Payment -->
        </div>

        <!-- Pending Registrations Table -->
        <div class="row mt-4">
            <div class="col-lg-7">
                <div class="hero_box_content">
                    <h5>និស្សិតស្នើសុំស្នាក់នៅ</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ល.រ</th>
                                <th scope="col">លេខសម្គាល់និស្សិត</th>
                                <th scope="col">ឈ្មោះនិស្សិត</th>
                                <th scope="col">ជំនាញ</th>
                                <th scope="col">ឆ្នាំសិក្សា</th>
                                <th scope="col">កម្រិតសិក្សា</th>
                                <th scope="col">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_pending_students->num_rows > 0): ?>
                                <?php $i = 1; ?>
                                <?php while ($row = $result_pending_students->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row["lastname"] . " " . $row["name"]); ?></td>
                                        <td><?php echo htmlspecialchars($row['skill']); ?></td>
                                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                                        <td><?php echo htmlspecialchars($row['education_level']); ?></td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No pending registrations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="hero_box_content">
                    <h5>និស្សិតស្នើសុំច្បាប់</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ល.រ</th>
                                <th scope="col">លេខសម្គាល់និស្សិត</th>
                                <th scope="col">ឈ្មោះនិស្សិត</th>
                                <th scope="col">ចំនួនថ្ងៃ</th>
                                <th scope="col">មូលហេតុ</th>
                                <th scope="col">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_pending_leave->num_rows > 0): ?>
                                <?php $i = 1; ?>
                                <?php while ($row = $result_pending_leave->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row["user_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($row['sumday']); ?></td>
                                        <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No pending registrations found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>