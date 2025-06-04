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

// Pagination and search filters
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$search_student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';
$search_skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
$search_year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';

$offset = ($page - 1) * $limit;

$sql_select = "
    SELECT r.*
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";
if ($search_student_id) {
    $sql_select .= " AND r.student_id LIKE '%$search_student_id%'";
}
if ($search_skill) {
    $sql_select .= " AND r.skill = '$search_skill'";
}
if ($search_year) {
    $sql_select .= " AND r.year = '$search_year'";
}
$sql_select .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($sql_select);
$unlisted_students_count = $result->num_rows;

$sql_count = "
    SELECT COUNT(*) as count
    FROM register r
    LEFT JOIN payment p ON r.student_id = p.student_id
    WHERE r.status = 'អនុញ្ញាត' AND p.student_id IS NULL
";
if ($search_student_id) {
    $sql_count .= " AND r.student_id LIKE '%$search_student_id%'";
}
if ($search_skill) {
    $sql_count .= " AND r.skill = '$search_skill'";
}
if ($search_year) {
    $sql_count .= " AND r.year = '$search_year'";
}
$count_result = $conn->query($sql_count);
$total_rows = $count_result->fetch_assoc()['count'];
$pages = ceil($total_rows / $limit);

// Select summary payment -----------------------------------------------
$checkSql = "SELECT * FROM payment_summary LIMIT 1";
$result = mysqli_query($conn, $checkSql);

if ($result && mysqli_num_rows($result) > 0) {
    $payment_summary = mysqli_fetch_assoc($result); // All fields stored
} else {
    $payment_summary = null;
}
// End select summary payment -------------------------------------------

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

                        <!-- Right Side: Info and Actions -->
                        <div class="d-flex flex-column flex-md-row flex-wrap gap-3 align-items-start"
                            style="gap: 0.5rem;">
                            <div class="d-flex flex-wrap flex-column flex-sm-row gap-3" style="gap: 0.5rem;">
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

                                <!-- Update Button -->
                                <a href="#" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addQrModal">ធ្វើបច្ចុប្បន្ន</a>
                            </div>

                            <!-- Bank Button -->
                            <a class="btn btn-warning text-white d-flex align-items-center gap-1 mt-2 mt-md-0"
                                href="qr-code-bank.php">
                                <!-- SVG omitted for brevity -->
                                Bank
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
                            <?php if ($unlisted_students_count > 0): ?>
                                <?php $i = $offset + 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($row["student_id"]); ?></td>
                                        <td><?= htmlspecialchars($row["lastname"] . " " . $row["name"]); ?></td>
                                        <td><?= htmlspecialchars($row["gender"]); ?></td>
                                        <td><?= htmlspecialchars($row["dob"]); ?></td>
                                        <td><?= htmlspecialchars($row["address"]); ?></td>
                                        <td><?= htmlspecialchars($row["phone_student"]); ?></td>
                                        <td><?= htmlspecialchars($row["skill"]); ?></td>
                                        <td><?= htmlspecialchars($row["education_level"]); ?></td>
                                        <td><?= htmlspecialchars($row["year"]); ?></td>
                                        <td>
                                            <a href="pay.php?student_id=<?= $row["student_id"]; ?>" class="btn btn-success">
                                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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

                <div class="modal-body">
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

</html>