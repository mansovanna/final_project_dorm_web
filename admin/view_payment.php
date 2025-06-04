<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}


// Handle get qr code form data base that show
$get_data = "SELECT * FROM qr_code_bank";
$result_qr = $conn->query($get_data);

if ($result_qr->num_rows > 0) {
    while ($row_qr = $result_qr->fetch_assoc()) {
        $qr_codes = $row_qr;
    }
} else {
    $qr_codes = null;
}


// Select summary payment -----------------------------------------------
$checkSql = "SELECT * FROM payment_summary LIMIT 1";
$result = mysqli_query($conn, $checkSql);

if ($result && mysqli_num_rows($result) > 0) {
    $payment_summary = mysqli_fetch_assoc($result); // All fields stored
} else {
    $payment_summary = null;
}
// End select summary payment -------------------------------------------

// Pagination and search filters
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
$search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
$search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

$offset = ($page - 1) * $limit;

$currentYear = date('Y');

// Create dynamic WHERE clause
$where = "WHERE 1=1";
if (!empty($search_student_id)) {
    $where .= " AND s.student_id LIKE '%$search_student_id%'";
}
if (!empty($search_skill)) {
    $where .= " AND s.skill LIKE '%$search_skill%'";
}

// Get total count (for pagination)
$countQuery = "SELECT COUNT(*) AS total FROM register s $where";
$countResult = mysqli_query($conn, $countQuery);
$total = mysqli_fetch_assoc($countResult)['total'];

// Main query
$query = "SELECT s.student_id, s.name, s.lastname, s.skill, YEAR(s.stay) AS start_year, s.gender, s.dob, s.address, s.phone_student, s.education_level
              FROM register s 
              $where 
              ORDER BY s.student_id 
              LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
if (!$result) {
    return [
        'status' => 'error',
        'message' => 'Query failed: ' . mysqli_error($conn)
    ];
}

$rows = [];

while ($row = mysqli_fetch_assoc($result)) {
    $studentId = $row['student_id'];
    $name = $row['name'];
    $lastname = $row['lastname'];
    $skill = $row['skill'];
    $startYear = $row['start_year'];
    $gender = $row['gender'];
    $dob = $row['dob'];
    $address = $row['address'];
    $phone_student = $row['phone_student'];
    $education_level = $row['education_level'];

    // Optional year filter: skip years not matching the filter
    $fromYear = $startYear;
    $toYear = $currentYear;
    if ($search_year > 0) {
        $fromYear = $toYear = $search_year;
    }

    // Get payments per student
    $paymentQuery = "SELECT YEAR(payment_date) AS paid_year, status 
                         FROM payment 
                         WHERE student_id = '$studentId'";
    $paymentResult = mysqli_query($conn, $paymentQuery);

    $paymentsByYear = [];
    while ($payRow = mysqli_fetch_assoc($paymentResult)) {
        $paymentsByYear[$payRow['paid_year']] = $payRow['status'];
    }

    // Build rows per year
    for ($year = $fromYear; $year <= $toYear; $year++) {
        $status = isset($paymentsByYear[$year]) ? $paymentsByYear[$year] : 'Pedding';
        switch ($status) {
            case 'Approved':
            $message = 'បានបង់ប្រាក់រួចរាល់';
            break;
            case 'Reject':
            $message = 'បដិសេធ';
            break;
            case 'Pedding':
            $message = 'កំពុងរង់ចាំអនុម័ត';
            break;
            default:
            $message = 'មិនទាន់បានបង់ប្រាក់';
            break;
        }

        $rows[] = [
            'student_id' => $studentId,
            'name' => $name,
            'lastname' => $lastname,
            'skill' => $skill,
            'year' => $year,
            'message' => $message,
            'gender' => $gender,
            'dob' => $dob,
            'address' => $address,
            'phone_student' => $phone_student,
            'education_level' => $education_level,
            'status' => isset($paymentsByYear[$year]) ? $paymentsByYear[$year] : 'Not Paid'
        ];
    }
}

// Store data for use in HTML
// $students_data = [
//     'status' => 'success',
//     'total' => $total,
//     'page' => $page,
//     'limit' => $limit,
//     'data' => $rows
// ];
$pages = ceil($total / $limit);


$conn->close();
?>
<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>

<body>

    <div class="content-wrapper">
        <h3 style="font-weight: bold; margin-bottom: 20px;">បញ្ជីនិស្សិតមិនទាន់មានក្នុងប្រព័ន្ធបង់ប្រាក់</h3>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>លេខសម្គាល់និស្សិត</label>
                            <input type="text" name="student_id" class="form-control"
                                value="<?= htmlspecialchars($search_student_id); ?>">
                        </div>
                        <div class="col-md-4">
                            <label>ជំនាញ</label>
                            <select name="skill" class="form-control">
                                <option value="">ជ្រើសរើសជំនាញ</option>
                                <?php
                                $skills = ["បច្ចេកវិទ្យាកុំព្យូទ័រ", "វិទ្យាសាស្ត្រដំណាំ", "គីមីចំណីអាហារ", "បច្ចេកវិទ្យាអគ្គីសនី", "មេកានិច", "វិទ្យាសាស្ត្រសត្វ"];
                                foreach ($skills as $skill) {
                                    $selected = ($search_skill == $skill) ? 'selected' : '';
                                    echo "<option value=\"$skill\" $selected>$skill</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>ឆ្នាំសិក្សា</label>
                            <select name="year" class="form-control">
                                <option value="">ឆ្នាំ</option>
                                <?php for ($y = 1; $y <= 4; $y++): ?>
                                    <option value="<?= $y ?>" <?= ($search_year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3 mb-3">

                        <!-- Left Side: Buttons -->
                        <div class="d-flex flex-wrap gap-2" style="gap: 0.5rem;">
                            <button type="submit" class="btn btn-primary">ស្វែងរក</button>
                            <a href="no_pay.php" class="btn btn-danger">សម្អាត</a>
                            <a href="export/export_no_pay.php?skill=<?= urlencode($search_skill); ?>&year=<?= urlencode($search_year); ?>&student_id=<?= urlencode($search_student_id); ?>"
                                class="btn btn-success">ទាញយក</a>
                        </div>




                    </div>

                    <!--  -->
                    <div class="d-flex flex-wrap flex-column flex-sm-row justify-content-between gap-3"
                        style="gap: 0.5rem; margin-bottom: 10px;">
                        <div class="d-flex flex-wrap flex-column flex-sm-row gap-3">
                            <!-- ថ្លៃស្នាក់នៅ -->
                            <div class="d-flex align-items-center gap-2">
                                <strong>ថ្លៃស្នាក់នៅ:</strong>
                                <p class="mb-0">
                                    <?= isset($payment_summary['room']) ? $payment_summary['room'] : '0' ?>
                                </p>
                            </div>

                            <!-- ថ្លៃភ្លើង -->
                            <div class="d-flex align-items-center gap-2">
                                <strong>ថ្លៃភ្លើង:</strong>
                                <p class="mb-0">
                                    <?= isset($payment_summary['electricity_fee']) ? $payment_summary['electricity_fee'] : '0' ?>
                                </p>
                            </div>

                            <!-- ថ្លៃទឹក -->
                            <div class="d-flex align-items-center gap-2">
                                <strong>ថ្លៃទឹក:</strong>
                                <p class="mb-0">
                                    <?= isset($payment_summary['water_fee']) ? $payment_summary['water_fee'] : '0' ?>
                                </p>
                            </div>

                            <!-- បញ្ចុះតម្លៃ -->
                            <div class="d-flex align-items-center gap-2">
                                <strong>បញ្ចុះតម្លៃ:</strong>
                                <p class="mb-0">
                                    <?= isset($payment_summary['discount']) ? $payment_summary['discount'] : '0' ?>
                                </p>
                            </div>

                            <!-- តម្លៃសរុប -->
                            <div class="d-flex align-items-center gap-3" style="gap: 0.5rem;">
                                <strong>តម្លៃសរុប:</strong>
                                <p class="mb-0">
                                    <?= isset($payment_summary['total']) ? $payment_summary['total'] : '0' ?>
                                </p>
                            </div>
                        </div>

                        <!-- Update Button -->

                        <!-- Right Side: Info and Actions -->
                        <div class="d-flex flex-column flex-md-row flex-wrap gap-3 align-items-start"
                            style="gap: 0.5rem;">
                            <a href="#" class="btn btn-primary " data-toggle="modal" data-target="#addQrModal">
                                ធ្វើបច្ចុប្បន្នតម្លៃត្រូវបង់

                            </a>
                            <!-- Bank Button -->
                            <a class="btn btn-warning text-white d-flex align-items-center gap-3 mt-2 mt-md-0"
                                href="qr-code-bank.php">
                                <!-- SVG omitted for brevity -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                                    fill="none">
                                    <path
                                        d="M3 6C3 4.58579 3 3.87868 3.43934 3.43934C3.87868 3 4.58579 3 6 3C7.41421 3 8.12132 3 8.56066 3.43934C9 3.87868 9 4.58579 9 6C9 7.41421 9 8.12132 8.56066 8.56066C8.12132 9 7.41421 9 6 9C4.58579 9 3.87868 9 3.43934 8.56066C3 8.12132 3 7.41421 3 6Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M3 18C3 16.5858 3 15.8787 3.43934 15.4393C3.87868 15 4.58579 15 6 15C7.41421 15 8.12132 15 8.56066 15.4393C9 15.8787 9 16.5858 9 18C9 19.4142 9 20.1213 8.56066 20.5607C8.12132 21 7.41421 21 6 21C4.58579 21 3.87868 21 3.43934 20.5607C3 20.1213 3 19.4142 3 18Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path d="M3 12L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M12 3V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M15 6C15 4.58579 15 3.87868 15.4393 3.43934C15.8787 3 16.5858 3 18 3C19.4142 3 20.1213 3 20.5607 3.43934C21 3.87868 21 4.58579 21 6C21 7.41421 21 8.12132 20.5607 8.56066C20.1213 9 19.4142 9 18 9C16.5858 9 15.8787 9 15.4393 8.56066C15 8.12132 15 7.41421 15 6Z"
                                        stroke="currentColor" stroke-width="1.5" />
                                    <path
                                        d="M21 12H15C13.5858 12 12.8787 12 12.4393 12.4393C12 12.8787 12 13.5858 12 15M12 17.7692V20.5385M15 15V16.5C15 17.9464 15.7837 18 17 18C17.5523 18 18 18.4477 18 19M16 21H15M18 15C19.4142 15 20.1213 15 20.5607 15.44C21 15.8799 21 16.5881 21 18.0043C21 19.4206 21 20.1287 20.5607 20.5687C20.24 20.8898 19.7767 20.9766 19 21"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                </svg>
                                <p class="mb-0 ml-1">Bank</p>

                            </a>
                        </div>
                    </div>

                </form>

                <div class="table-responsive table-hover">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ល.រ</th>
                                <th>លេខសម្គាល់និស្សិត</th>
                                <th>ឈ្មោះនិស្សិត</th>
                                <th>ភេទ</th>
                                <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                                <th>អាសយដ្ឋាន</th>
                                <th>លេខទូរស័ព្ទនិស្សិត</th>
                                <th>ជំនាញ</th>
                                <th>កម្រិតសិក្សា</th>
                                <th>ឆ្នាំសិក្សា</th>
                                <th>សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($rows) > 0): ?>
                                <?php $i = $offset + 1; ?>
                                <?php foreach ($rows as $row): 
                                        if($row["status"] !=="Approved"):
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($row["student_id"]); ?></td>
                                        <td><?= htmlspecialchars($row["lastname"] . " " . $row["name"]); ?></td>
                                        <td><?= isset($row["gender"]) ? htmlspecialchars($row["gender"]) : '-'; ?></td>
                                        <td><?= isset($row["dob"]) ? htmlspecialchars($row["dob"]) : '-'; ?></td>
                                        <td><?= isset($row["address"]) ? htmlspecialchars($row["address"]) : '-'; ?></td>
                                        <td><?= isset($row["phone_student"]) ? htmlspecialchars($row["phone_student"]) : '-'; ?></td>
                                        <td><?= htmlspecialchars($row["skill"]); ?></td>
                                        <td><?= isset($row["education_level"]) ? htmlspecialchars($row["education_level"]) : '-'; ?></td>
                                        <td><?= htmlspecialchars($row["year"]); ?></td>
                                        <td>
                                            <a href="pay.php?student_id=<?= $row["student_id"]; ?>&year=<?= $row["year"]; ?>" class="btn btn-success">
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            </a>
                                            <?php
                                            // Display status badge based on payment status
                                            if ($row["status"] === 'Pending') 
                                            {
                                                // Show a warning badge with a clock icon for Pending status
                                            
                                                echo ' <a href="view_payment.php?student_id=' . urlencode($row["student_id"]) . '&year=' . urlencode($row["year"]) . '" class="btn btn-warning text-white btn-sm ml-1"><i class="fa fa-clock"></i> កំពុងរង់ចាំពិនិត្យ</a>';
                                            } elseif ($row["status"] === 'Approved') {
                                                echo '<span class="badge badge-success p-2">បានបង់ប្រាក់រួចរាល់</span>';
                                            } elseif ($row["status"] === 'Reject') {
                                                echo '<span class="badge badge-secondary p-2">បដិសេធ</span>';
                                            }else{
                                                // badge badge-danger
                                                echo '<span class="text-danger p-2" style="font-size: 0.8rem;"><i class="fa fa-times-circle"></i> មិនទាន់បង់ប្រាក់</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>

                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">មិនមានទិន្នន័យ</td>
                                </tr>
                            <?php endif; ?>

                        
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item <?= $page == 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page - 1; ?>&limit=<?= $limit; ?>">«</a>
                            </li>
                            <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?= $i; ?>&limit=<?= $limit; ?>"><?= $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page == $pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?= $page + 1; ?>&limit=<?= $limit; ?>">»</a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>

</body>

</html>