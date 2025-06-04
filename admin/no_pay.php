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

// Initialize search parameters
// Pagination and search filters
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
    $search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
    $search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

    $offset = ($page - 1) * $limit;
    $payment_students = getDataServer($conn);
    $total = 0;
function getDataServer($conn)
{
    global $search_student_id, $search_skill, $search_year;

    // Build SQL with search filters
    $where = [];
    if (!empty($search_student_id)) {
        $where[] = "student_id LIKE '%$search_student_id%'";
    }
    if (!empty($search_skill)) {
        $where[] = "skill LIKE '%$search_skill%'";
    }
    if (!empty($search_year)) {
        $where[] = "year = '$search_year'";
    }
    $whereClause = '';
    if (!empty($where)) {
        $whereClause = 'WHERE ' . implode(' AND ', $where);
    }

    $totalSql = "SELECT COUNT(*) as total FROM register $whereClause";
    $totalResult = mysqli_query($conn, $totalSql);
    $totalRow = mysqli_fetch_assoc($totalResult);
    $total = $totalRow['total'] ?? 0;

    $sql = "SELECT * FROM register $whereClause";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . mysqli_error($conn)
        ];
    }
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Get start year from 'stay' field
        $startYear = is_numeric($row['stay']) ? (int) $row['stay'] : (int) date('Y', strtotime($row['stay']));
        $currentYear = (int) date('Y');
        $years = [];
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }

        // For each year, add a record with user info and payment status
        foreach ($years as $year) {
            $paymentStatus = getPaymentStatusByYear($conn, $row['student_id'], $year);
           
            if ($row['year'] != (int)$paymentStatus['date'] && $paymentStatus['status'] === 'Approved') {
                // If the student has paid for this year, skip adding them
                continue;
            } else {
                $data[] = [
                    'student_id' => $row['student_id'],
                    'name' => $row['name'],
                    'lastname' => $row['lastname'],
                    'gender' => $row['gender'],
                    'dob' => $row['dob'],
                    'stay' => date('Y', strtotime($row['stay'])),
                    'building' => $row['building'],
                    'room_number' => $row['room'],
                    'phone_student' => $row['phone_student'],
                    'skill' => $row['skill'],
                    'education_level' => $row['education_level'],
                    'year' => $year,
                    'payment_data' => $paymentStatus['date'] ?? null,
                    'payment_status' => $paymentStatus['status'] ?? null,
                    'payment_id' => $paymentStatus['id'] ?? null
                ];
            }
        }
    }

    return $data;
}
getDataServer($conn);

// Helper function to get payment status for a student in a specific year
function getPaymentStatusByYear($conn, $student_id, $year)
{
    $sql = "SELECT id, status, date FROM payment 
            WHERE student_id = '" . mysqli_real_escape_string($conn, $student_id) . "' 
            AND `date` = '" . intval($year) . "'";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return [
            'status' => $row['status'],
            'date' => $row['date']?? null,
            'id' => $row['id'] ?? null
        ];
    }

    return [
        'status' => null,
        'date' => null,
        'id' => null
    ];
}




$pages = ceil($total / $limit);

?>
<?php 

include("../include/header.php");

?>
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
                        <div class="d-flex flex-wrap flex-column flex-sm-row" style="gap: 1rem;">
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
                            <?php if (count($payment_students) > 0): ?>
                                <?php $i = $offset + 1; ?>
                                <?php foreach ($payment_students as $row): 
                                        // if($row["status"] !=="Approved"):
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
                                            <a href="pay.php?student_id=<?= $row["student_id"]; ?>&year=<?= $row["year"]; ?>&payment_id=<?= $row["payment_id"]??'0'; ?>" class="btn btn-success">
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            </a>
                                            <?php
                                            // Display status badge based on payment status
                                            if ($row["payment_status"] === 'Pending') 
                                            {
                                                // Show a button to open modal with payment details and images
                                                echo '<button type="button" class="btn btn-warning text-white btn-sm ml-1" data-toggle="modal" data-target="#paymentModal_' . htmlspecialchars($row["student_id"]) . '_' . htmlspecialchars($row["year"]) . '">
                                                    <i class="fa fa-clock"></i> កំពុងរង់ចាំពិនិត្យ
                                                </button>';

                                                // Fetch all payment records for this student and year
                                                $studentId = $row["student_id"];
                                                $year = $row["year"];
                                                $paymentDetails = [];
                                                $paymentQuery = "SELECT * FROM payment WHERE student_id = '$studentId' AND status = 'Pending'";
                                                // Execute the query to get payment details
                                                $paymentResult = mysqli_query($conn, $paymentQuery);
                                                if ($paymentResult && mysqli_num_rows($paymentResult) > 0) {
                                                    while ($payRow = mysqli_fetch_assoc($paymentResult)) {
                                                        $paymentDetails[] = $payRow;
                                                    }
                                                }
                                                // Modal for payment details
                                                ?>
                                                <div class="modal fade" id="paymentModal_<?= htmlspecialchars($row["student_id"]) ?>_<?= htmlspecialchars($row["year"]) ?>" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel_<?= htmlspecialchars($row["student_id"]) ?>_<?= htmlspecialchars($row["year"]) ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="paymentModalLabel_<?= htmlspecialchars($row["student_id"]) ?>_<?= htmlspecialchars($row["year"]) ?>">ព័ត៌មានបង់ប្រាក់</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php if (!empty($paymentDetails)): ?>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>ថ្ងៃបង់ប្រាក់</th>
                                                                                    <th>ចំនួន</th>
                                                                                    <th>ស្ថានភាព</th>
                                                                                    <th>រូបភាព</th>
                                                                                    <th>ផ្សេងៗ</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php foreach ($paymentDetails as $detail): ?>
                                                                                    <tr>
                                                                                        <td><?= htmlspecialchars($detail['payment_date']) ?></td>
                                                                                        <td><?= htmlspecialchars($detail['total_fee']) ?></td>
                                                                                        <td><?= htmlspecialchars($detail['status']) ?></td>
                                                                                        <td>
                                                                                            <?php if (!empty($detail['image'])): ?>
                                                                                                <a href="<?= htmlspecialchars('http://localhost/dorm_ksit/uploads/images_qr/'.$detail['image']) ?>" target="_blank">
                                                                                                    <img src="<?= htmlspecialchars('http://localhost/dorm_ksit/uploads/images_qr/' . $detail['image']) ?>" alt="រូបភាពបង់ប្រាក់" style="max-width:80px;max-height:80px; cursor: pointer;" data-toggle="modal" data-target="#imgModal_<?= $detail['id'] ?>">
                                                                                                </a>
                                                                                                <!-- Modal for big image -->
                                                                                                <div class="modal fade" id="imgModal_<?= $detail['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="imgModalLabel_<?= $detail['id'] ?>" aria-hidden="true">
                                                                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                                                                        <div class="modal-content">
                                                                                                            <div class="modal-header">
                                                                                                                <h5 class="modal-title" id="imgModalLabel_<?= $detail['id'] ?>">រូបភាពបង់ប្រាក់</h5>
                                                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                    <span aria-hidden="true">&times;</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                            <div class="modal-body text-center">
                                                                                                                <!-- Display the image in the modal -->
                                                                                                                <img src="<?= htmlspecialchars(string: 'http://localhost/dorm_ksit/uploads/images_qr/' . $detail['image']) ?>" alt="រូបភាពបង់ប្រាក់" style="max-width:100%;max-height:80vh;">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php else: ?>
                                                                                                <span class="text-muted">មិនមានរូបភាព</span>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                        <td><?= htmlspecialchars($detail['note'] ?? '') ?></td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="alert alert-warning">មិនមានព័ត៌មានបង់ប្រាក់សម្រាប់និស្សិតនេះ</div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            } elseif ($row["payment_status"] === 'Approved') {
                                                echo '<span class="badge badge-success p-2">បានបង់ប្រាក់រួចរាល់</span>';
                                            } elseif ($row["payment_status"] === 'Rejected') {
                                                echo '<span class="badge badge-secondary p-2">បដិសេធ</span>';
                                            }else{
                                                // badge badge-danger
                                                echo '<span class="text-danger p-2" style="font-size: 0.8rem;"><i class="fa fa-times-circle"></i> មិនទាន់បង់ប្រាក់</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php 
                            // endif;
                            endforeach; ?>

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

    <!-- Modal to add payment summary -->
    <div class="modal fade" id="addQrModal" tabindex="-1" role="dialog" aria-labelledby="addQrModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="add_payment.php" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">បន្ថែមព័ត៌មានបង់ប្រាក់</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body ">
                    <!-- Room Fee -->
                    <div class="form-group">
                        <label>ថ្លៃស្នាក់នៅ (៛)</label>
                        <input type="number" name="room" id="room" class="form-control"
                            value="<?= isset($payment_summary['room']) ? $payment_summary['room'] : '0' ?>" required>
                    </div>

                    <!-- Electricity Fee -->
                    <div class="form-group">
                        <label>ថ្លៃភ្លើង (៛)</label>
                        <input type="number" name="electricity_fee" id="electricity" class="form-control"
                            value="<?= isset($payment_summary['electricity_fee']) ? $payment_summary['electricity_fee'] : '0' ?>"
                            required>
                    </div>


                    <!-- ថ្លៃទឹក -->
                    <div class="form-group">
                        <label>ថ្លៃទឹក (៛)</label>
                        <input type="number" name="water_fee" id="water" class="form-control"
                            value="<?= isset($payment_summary['water_fee']) ? $payment_summary['water_fee'] : '0' ?>"
                            required>
                    </div>

                    <!-- បញ្ចុះតម្លៃ -->
                    <div class="form-group">
                        <label>បញ្ចុះតម្លៃ (%)</label>
                        <input type="number" name="discount" id="discount" class="form-control"
                            value="<?= isset($payment_summary['discount']) ? $payment_summary['discount'] : '0' ?>"
                            required>
                    </div>

                    <!-- តម្លៃសរុប -->
                    <div class="form-group">
                        <label>តម្លៃសរុប (៛)</label>
                        <input type="text" id="total" class="form-control bg-light" readonly
                            value="<?= isset($payment_summary['total']) ? $payment_summary['total'] : '0' ?>">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">បិទ</button>
                    <button type="submit" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>


    <!-- JavaScript to calculate total -->
    <script>
        function calculateTotal() {
            const room = parseFloat(document.getElementById('room').value) || 0;
            const electricity = parseFloat(document.getElementById('electricity').value) || 0;
            const water = parseFloat(document.getElementById('water').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;

            const subtotal = room + electricity + water;
            const discountAmount = subtotal * (discount / 100);
            const total = subtotal - discountAmount;

            document.getElementById('total').value = total.toFixed(2);
        }

        // Attach event listeners
        document.getElementById('room').addEventListener('input', calculateTotal);
        document.getElementById('electricity').addEventListener('input', calculateTotal);
        document.getElementById('water').addEventListener('input', calculateTotal);
        document.getElementById('discount').addEventListener('input', calculateTotal);

        // Optional: recalculate total on page load
        window.addEventListener('load', calculateTotal);
    </script>





</body>

<?php
// Close the connection after all queries are done
$conn->close();
?>

</body>

</html>