<?php
session_start();
// Include database connection
include '../conn_db.php';

// Initialize search variables
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Construct the SQL query with search filters
$sql_select = "SELECT * FROM register WHERE status = 'អនុញ្ញាត'";

$conditions = [];
$params = [];

if (!empty($skill)) {
    $conditions[] = "skill LIKE ?";
    $params[] = "%$skill%";
}
if (!empty($student_id)) {
    $conditions[] = "student_id LIKE ?";
    $params[] = "%$student_id%";
}
if (!empty($year)) {
    $conditions[] = "year LIKE ?";
    $params[] = "%$year%";
}

if (count($conditions) > 0) {
    $sql_select .= " AND " . implode(" AND ", $conditions);
}

// Add limit and offset for pagination
$sql_select .= " LIMIT $start, $limit";

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql_select);

// Bind parameters
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Close the statement and connection
$stmt->close();

// Get total number of rows for pagination
$sql_count = "SELECT COUNT(*) AS total FROM register WHERE status = 'អនុញ្ញាត'";
if (count($conditions) > 0) {
    $sql_count .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql_count);
if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$count_result = $stmt->get_result();
$total = $count_result->fetch_assoc()['total'];
$pages = ceil($total / $limit);

// Close the statement and connection
$stmt->close();
$conn->close();

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
    <h3 class="mt-3" style="font-weight: bold;"> បញ្ជីឈ្មោះនិស្សិតស្នាក់នៅ</h3>
    


    <div class="card card-primary card-outline">
        <div class="card-body">
        <form method="GET" action="">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>លេខសម្គាល់និស្សិត</label>
                    <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo htmlspecialchars($student_id); ?>">
                </div>
                <div class="col-md-4">
                    <label>ជំនាញ</label>
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
                <a href="register_report.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                <!-- <button class="btn btn-success btn-shadow" onclick="exportToExcel()">ទាញយក</button> -->
                <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_register.php?skill=<?php echo $skill; ?>&year=<?php echo $year; ?>&student_id=<?php echo $student_id; ?>'">ទាញយក</button>
                <button class="btn btn-warning btn-shadow" onclick="printReport()">បោះពុម្ភ</button>

            </div>
            </form>


            <div class="table-responsive">
            <p class="mt-3" style="font-weight: bold;">របាយការណ៏និស្សិតសរុប: <?php echo $total; ?> </p>
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead">
                        <tr>
                            <th>ល.រ</th>
                            <th>លេខសម្គាល់និស្សិត</th>
                            <th>ឈ្មោះនិស្សិត</th>
                            <th>អគារស្នាក់នៅ</th>
                            <th>បន្ទប់លេខ</th>
                            <th>ថ្ងៃចូលស្នាក់នៅ</th>
                            <th>លេខទូរសព្ទ័និស្សិត</th>
                            <th>ស្ថានភាព</th>  
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo ($row["student_id"]); ?></td>
                        <td><?php echo $row["lastname"] . " " . $row["name"]; ?></td>
                        <td><?php echo ($row["building"]); ?></td>
                        <td><?php echo ($row["room"]); ?></td>
                        <td><?php echo ($row["stay"]); ?></td>
                        <td><?php echo ($row["phone_student"]); ?></td>
                        <td><?php echo ($row["status"]); ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                    ?>
                    <tr><td colspan="8">No data available</td></tr>
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function printReport() {
        const skill = '<?php echo $skill; ?>';
        const student_id = '<?php echo $student_id; ?>';
        const year = '<?php echo $year; ?>';
        const printWindow = window.open(`print/print_register_report.php?skill=${skill}&student_id=${student_id}&year=${year}`, '_blank');
    }
</script>

</body>
</html>
