<?php
session_start();
include '../conn_db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize search parameters
$first_date = isset($_GET['first_date']) ? $_GET['first_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit is 10
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default page is 1

// Calculate offset for pagination
$offset = ($page - 1) * $limit;

// Build SQL query with search criteria
$sql = "SELECT lr.student_id, lr.user_name, r.skill,
                MIN(lr.first_date) as first_date, 
                MAX(lr.end_date) as end_date, 
                GROUP_CONCAT(lr.reason SEPARATOR ', ') as reason, 
                COUNT(lr.reason) as leave_count, 
                lr.status 
        FROM reques_alaw lr
        JOIN register r ON lr.student_id = r.student_id
        WHERE lr.status = 'អនុញ្ញាត'";

if ($first_date) {
    $first_date = date('Y-m-d', strtotime($first_date)); // Normalize date format
    $sql .= " AND lr.first_date >= '" . $conn->real_escape_string($first_date) . " 00:00:00'";
}
if ($end_date) {
    $end_date = date('Y-m-d', strtotime($end_date)); // Normalize date format
    $sql .= " AND lr.end_date <= '" . $conn->real_escape_string($end_date) . " 23:59:59'";
}
if ($student_id) {
    $sql .= " AND lr.student_id LIKE '%" . $conn->real_escape_string($student_id) . "%'";
}
if ($skill) { // Add skill filter to the query
    $sql .= " AND r.skill LIKE '%" . $conn->real_escape_string($skill) . "%'";
}

$sql .= " GROUP BY lr.student_id";
$sql .= " LIMIT " . $limit . " OFFSET " . $offset;

$result = $conn->query($sql);

// Get the total number of records
$total_result = $conn->query("SELECT COUNT(DISTINCT lr.student_id) AS total_records
                              FROM reques_alaw lr
                              JOIN register r ON lr.student_id = r.student_id
                              WHERE lr.status = 'អនុញ្ញាត'" .
                              ($first_date ? " AND lr.first_date >= '" . $conn->real_escape_string($first_date) . " 00:00:00'" : '') .
                              ($end_date ? " AND lr.end_date <= '" . $conn->real_escape_string($end_date) . " 23:59:59'" : '') .
                              ($student_id ? " AND lr.student_id LIKE '%" . $conn->real_escape_string($student_id) . "%'" : ''));

$total_records = $total_result->fetch_assoc()['total_records'];
$total_pages = ceil($total_records / $limit);

// Fetch all records
$records = [];
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}
?>


<?php include("../include/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Report</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
    <h3 class="mt-3" style="font-weight: bold;"> របាយការណ៏និស្សិតសុំច្បាប់</h3>

    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>លេខសម្គាល់និស្សិត</label>
                        <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo htmlspecialchars($student_id); ?>">
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="from-date">ចាប់ពីថ្ងៃ</label>
                        <input type="date" id="from-date" name="first_date" class="form-control" value="<?php echo htmlspecialchars($first_date); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="to-date">ដល់ថ្ងៃ</label>
                        <input type="date" id="to-date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                    <a href="report_leave.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                    <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_leave_report.php?end_date=<?php echo $end_date; ?>&first_date=<?php echo $first_date; ?>&student_id=<?php echo $student_id; ?>&skill=<?php echo $skill; ?>'">ទាញយក</button>
                    <button type="button" class="btn btn-warning btn-shadow" onclick="printReport()">បោះពុម្ភ</button>
                </div>
            </form>

            <div class="table-responsive">
                <!-- <h4 class="mt-3" style="font-weight: bold;">របាយការណ៏និស្សិតសុំច្បាប់</h4> -->
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead">
                        <tr>
                            <th>ល.រ</th>
                            <th>លេខសម្គាល់និស្សិត</th>
                            <th>ឈ្មោះនិស្សិត</th>
                            <th>ជំនាញ</th>
                            <th>ចំនួនការសុំច្បាប់</th>   
                            <th width="5%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($total_records > 0) {
                        $i = $offset + 1;
                        foreach ($records as $row) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars($row["student_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["user_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["skill"]); ?></td>
                        <td><?php echo htmlspecialchars($row["leave_count"]); ?></td>
                        <td><a href="his_leav_report.php?student_id=<?= urlencode($row['student_id']) ?>" class="btn btn-info"><i class="fa-regular fa-eye"></i></a></td>
                    </tr>
                    <?php
                        }
                    } else {
                    ?>  
                    <tr><td colspan="6">No data available</td></tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page > 1){ echo "?page=" . ($page - 1) . "&first_date=" . $first_date . "&end_date=" . $end_date . "&student_id=" . $student_id . "&limit=" .$limit; } ?>"><i class="fa-solid fa-angles-left"></i></a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if($page == $i) {echo 'active';} ?>">
                            <a class="page-link" href="?page=<?= $i; ?>&first_date=<?= $first_date; ?>&end_date=<?= $end_date; ?>&student_id=<?= $student_id; ?>&limit=<?= $limit; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page Link -->
                    <li class="page-item <?php if($page >= $total_pages) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page < $total_pages) { echo "?page=" . ($page + 1) . "&first_date=" . $first_date . "&end_date=" . $end_date . "&student_id=" . $student_id . "&limit=" .$limit;} ?>">Next</a>
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
                <input type="hidden" name="first_date" value="<?= $first_date ?>">
                <input type="hidden" name="end_date" value="<?= $end_date ?>">
                <input type="hidden" name="student_id" value="<?= $student_id ?>">
            </form>
            
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function printReport() {
        const skill = '<?php echo $skill; ?>';
        const student_id = '<?php echo $student_id; ?>';
        const end_date = '<?php echo $end_date; ?>';
        const first_date = '<?php echo $first_date; ?>';
        const printWindow = window.open(`print/print_leave_report.php?skill=${skill}&student_id=${student_id}&end_date=${end_date}&first_date=${first_date}`, '_blank');
    }
</script>

</body>
</html>
