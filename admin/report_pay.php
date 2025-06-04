<?php
session_start();
require_once('../conn_db.php');
include("../include/header.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search parameters
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit is 10
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default page is 1

// Calculate offset for pagination
$offset = ($page - 1) * $limit;

// Build SQL query with search criteria
$sql = "SELECT p.student_id, r.name, r.lastname, p.building, p.room_number, p.accommodation_fee, p.discount, p.water_fee, p.electricity_fee, p.total_fee, p.payment_date, p.status
          FROM payment p 
          INNER JOIN register r ON p.student_id = r.student_id
          WHERE 1=1";

// Append search filters
$types = '';
$params = [];
if ($student_id) {
    $sql .= " AND p.student_id = ?";
    $types .= 's';
    $params[] = $student_id;
}
if ($skill) {
    $sql .= " AND r.skill = ?";
    $types .= 's';
    $params[] = $skill;
}
if ($year) {
    $sql .= " AND r.year = ?";
    $types .= 's';
    $params[] = $year;
}

// Append pagination
$sql .= " LIMIT ? OFFSET ?";
$types .= 'ii'; // LIMIT and OFFSET are integers
$params[] = $limit;
$params[] = $offset;

$stmt = $conn->prepare($sql);

// Check if prepare was successful
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters
$stmt->bind_param($types, ...$params);

// Execute statement
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

// Calculate total pages for pagination
$countQuery = "SELECT COUNT(*) AS total
               FROM payment p
               INNER JOIN register r ON p.student_id = r.student_id
               WHERE 1=1";
$countTypes = '';
$countParams = [];
if ($student_id) {
    $countQuery .= " AND p.student_id = ?";
    $countTypes .= 's';
    $countParams[] = $student_id;
}
if ($skill) {
    $countQuery .= " AND r.skill = ?";
    $countTypes .= 's';
    $countParams[] = $skill;
}
if ($year) {
    $countQuery .= " AND r.year = ?";
    $countTypes .= 's';
    $countParams[] = $year;
}

$countStmt = $conn->prepare($countQuery);

// Check if prepare was successful
if (!$countStmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind parameters for count query
if ($countTypes) {
    $countStmt->bind_param($countTypes, ...$countParams);
}

// Execute count statement
if (!$countStmt->execute()) {
    die("Execute failed: " . $countStmt->error);
}

$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'];
$pages = ceil($totalRows / $limit);
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
    <div class="content-wrapper">
        <div class="title mt-3 mb-3">
            <h3 style="font-weight: bold;">របាយការណ៏បង់ថ្លៃស្នាក់នៅ</h3>
        </div>
        <div class="card card-primary card-outline">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>លេខសម្គាល់និស្សិត</label>
                            <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo htmlspecialchars($student_id); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="skill">ជំនាញ</label>
                            <select name="skill" id="skill" class="form-control">
                                <option value="" <?php echo !$skill ? 'selected' : ''; ?>>ជ្រើសរើសជំនាញ</option>
                                <option value="បច្ចេកវិទ្យាកុំព្យូទ័រ" <?php echo $skill == 'បច្ចេកវិទ្យាកុំព្យូទ័រ' ? 'selected' : ''; ?>>បច្ចេកវិទ្យាកុំព្យូទ័រ</option>
                                <option value="វិទ្យាសាស្ត្រដំណាំ" <?php echo $skill == 'វិទ្យាសាស្ត្រដំណាំ' ? 'selected' : ''; ?>>វិទ្យាសាស្ត្រដំណាំ</option>
                                <option value="គីមីចំណីអាហារ" <?php echo $skill == 'គីមីចំណីអាហារ' ? 'selected' : ''; ?>>គីមីចំណីអាហារ</option>
                                <option value="បច្ចេកវិទ្យាអគ្គីសនី" <?php echo $skill == 'បច្ចេកវិទ្យាអគ្គីសនី' ? 'selected' : ''; ?>>បច្ចេកវិទ្យាអគ្គីសនី</option>
                                <option value="មេកានិច" <?php echo $skill == 'មេកានិច' ? 'selected' : ''; ?>>មេកានិច</option>
                                <option value="វិទ្យាសាស្ត្រសត្វ" <?php echo $skill == 'វិទ្យាសាស្ត្រសត្វ' ? 'selected' : ''; ?>>វិទ្យាសាស្ត្រសត្វ</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="year">ឆ្នាំសិក្សា</label>
                            <select name="year" id="year" class="form-control">
                                <option value="" <?php echo !$year ? 'selected' : ''; ?>>ឆ្នាំ</option>
                                <option value="1" <?php echo $year == '1' ? 'selected' : ''; ?>>1</option>
                                <option value="2" <?php echo $year == '2' ? 'selected' : ''; ?>>2</option>
                                <option value="3" <?php echo $year == '3' ? 'selected' : ''; ?>>3</option>
                                <option value="4" <?php echo $year == '4' ? 'selected' : ''; ?>>4</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                        <a href="report_pay.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                        <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_pay.php?skill=<?php echo $skill; ?>&year=<?php echo $year; ?>&student_id=<?php echo $student_id; ?>'">ទាញយក</button>
                        <button class="btn btn-warning btn-shadow" onclick="printReport()">បោះពុម្ភ</button>
                    </div>
                    </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
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
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">មិនមានទិន្នន័យនិស្សិតទូទាត់!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                 <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($page > 1){ echo "?page=" . ($page - 1) . "&skill=" . $skill . "&year=" . $year . "&student_id=" . $student_id . "&limit=" . $limit; } ?>"><i class="fa-solid fa-angles-left"></i></a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?php if($page == $i) {echo 'active';} ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&skill=<?= $skill; ?>&year=<?= $year; ?>&student_id=<?= $student_id; ?>&limit=<?= $limit; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page Link -->
                        <li class="page-item <?php if($page >= $pages) { echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($page < $pages) { echo "?page=" . ($page + 1) . "&skill=" . $skill . "&year=" . $year . "&student_id=" . $student_id . "&limit=" . $limit; } ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <!-- End Pagination -->

                <!-- Pagination Limit -->
                <form method="GET" action="" class="form-inline" style="margin-top: -55px;">
                    <div class="form-group limit">
                        <label for="limit" class="mr-2">កំណត់ត្រាទំព័រ:</label>
                        <select class="form-control" name="limit" id="limit" onchange="this.form.submit()">
                            <?php
                            $start = 5;
                            $end = 50;
                            $increment = 5;
                            for ($i = $start; $i <= $end; $i += $increment) {
                                $selected = ($limit == $i) ? 'selected' : '';
                                echo "<option value=\"$i\" $selected>$i</option>";
                              }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="page" value="<?= $page ?>">
                    <input type="hidden" name="skill" value="<?= $skill ?>">
                    <input type="hidden" name="year" value="<?= $year ?>">
                    <input type="hidden" name="student_id" value="<?= $student_id ?>">
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function printReport() {
        const skill = '<?php echo $skill; ?>';
        const student_id = '<?php echo $student_id; ?>';
        const year = '<?php echo $year; ?>';
        const printWindow = window.open(`print/print_pay_report.php?skill=${skill}&student_id=${student_id}&year=${year}`, '_blank');
    }
</script>
</body>
</html>

