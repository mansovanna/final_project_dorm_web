<?php
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
include '../conn_db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status update via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status']) && isset($_POST['user_id'])) {
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $newStatus = ($_POST['status'] == 'អនុញ្ញាត') ? 'អនុញ្ញាត' : 'មិនអនុញ្ញាត';

    // Update the status in the database
    $sql = "UPDATE reques_alaw SET status = '$newStatus' WHERE user_id = '$user_id'";
    if ($conn->query($sql) !== TRUE) {
        die('Failed to update status.');
    }

    // Return a JSON response indicating success
    echo json_encode(['success' => true]);
    exit();
}

// Search parameters
$first_date = isset($_GET['first_date']) ? $conn->real_escape_string($_GET['first_date']) : '';
$end_date = isset($_GET['end_date']) ? $conn->real_escape_string($_GET['end_date']) : '';
$student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';

// Build the SQL query with search parameters
$conditions = ["status = 'រង់ចាំ'"];
if ($first_date && $end_date) {
    $conditions[] = "(first_date <= '$end_date' AND end_date >= '$first_date')";
}
if ($student_id) {
    $conditions[] = "student_id LIKE '%$student_id%'";
}
$sql_select = "SELECT * FROM reques_alaw WHERE " . implode(" AND ", $conditions);

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Add limit and offset for pagination
$sql_select .= " LIMIT $start, $limit";

// Get total number of rows for pagination
$sql_count = "SELECT COUNT(*) AS total FROM reques_alaw WHERE " . implode(" AND ", $conditions);
$count_result = $conn->query($sql_count);
$total = $count_result->fetch_assoc()['total'];
$pages = ceil($total / $limit);

$result = $conn->query($sql_select);
?>


<?php include("../include/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">ទិន្នន័យនិស្សិតសុំច្បាប់</h3>
    <div class="card card-primary card-outline">
        <div class="card-body">  
            <form method="GET" action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>លេខសម្គាល់និស្សិត</label>
                        <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo isset($_GET['student_id']) ? $_GET['student_id'] : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="from-date">ចាប់ពីថ្ងៃទី</label>
                        <input type="date" id="from-date" name="first_date" class="form-control" value="<?php echo isset($_GET['first_date']) ? $_GET['first_date'] : ''; ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="to-date">ដល់ថ្ងៃទី</label>
                        <input type="date" id="to-date" name="end_date" class="form-control" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                    <a href="leave_ad.php" type="reset" class="btn btn-danger btn-shadow">សម្អាត</a>
                    <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_leave_request.php?end_date=<?php echo $end_date; ?>&first_date=<?php echo $first_date; ?>&student_id=<?php echo $student_id; ?>'">ទាញយក</button>

                    <!-- <button type="button" class="btn btn-success btn-shadow" onclick="exportToExcel()">ទាញយក</button> -->
                </div>   
            </form> 

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead">
                        <tr>
                            <th>ល.រ</th>
                            <th>ID</th>
                            <th>ឈ្មោះនិស្សិត</th>
                            <th>ចំនួនថ្ងៃ</th>
                            <th>ចាប់ពីថ្ងៃទី</th>
                            <th>ដល់ថ្ងៃទី</th>
                            <th>មូលហេតុ</th>
                            <th width="15%;">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                if ($result->num_rows > 0) {
                        $i = $start + 1;
                        while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $row['student_id']; ?></td>
                            <td><?= $row['user_name']; ?></td>
                            <td><?= $row['sumday']; ?></td>
                            <td><?= $row['first_date']; ?></td>
                            <td><?= $row['end_date']; ?></td>
                            <td><?= $row['reason']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'អនុញ្ញាត') { ?>
                                    <button class='btn btn-success' disabled>បានអនុញ្ញាត</button>
                                <?php } elseif ($row['status'] == 'មិនអនុញ្ញាត') { ?>
                                    <button class='btn btn-danger' disabled>មិនអនុញ្ញាត</button>
                                <?php } else { ?>
                                    <button class='btn btn-success' onclick="updateStatus('<?= $row['user_id'] ?>', 'អនុញ្ញាត')"><i class="fa-solid fa-circle-check"></i></button>
                                    <button class='btn btn-danger' onclick="updateStatus('<?= $row['user_id'] ?>', 'មិនអនុញ្ញាត')"><i class="fa-solid fa-rectangle-xmark"></i></button>
                                <?php } ?>
                                <a href="his_leav.php?student_id=<?= $row['student_id'] ?>" class="btn btn-info"><i class="fa-regular fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php
                            }
                            } else {
                                ?>
                                <tr><td colspan="12">No data available</td></tr>
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
                    <?php for($i = 1; $i <= $pages; $i++): ?>
                        <li class="page-item <?php if($page == $i) {echo 'active';} ?>">
                            <a class="page-link" href="?page=<?= $i; ?>&first_date=<?= $first_date; ?>&end_date=<?= $end_date; ?>&student_id=<?= $student_id; ?>&limit=<?= $limit; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Page Link -->
                    <li class="page-item <?php if($page >= $pages) { echo 'disabled'; } ?>">
                        <a class="page-link" href="<?php if($page < $pages) { echo "?page=" . ($page + 1) . "&first_date=" . $first_date . "&end_date=" . $end_date . "&student_id=" . $student_id . "&limit=" .$limit;} ?>">Next</a>
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
                <input type="hidden" name="end_date" value="<?= $first_date ?>">
                <input type="hidden" name="first_date" value="<?= $end_date ?>">
                <input type="hidden" name="student_id" value="<?= $student_id ?>">
            </form>
            
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function updateStatus(user_id, status) {
        $.ajax({
            type: 'POST',
            url: '<?php echo $_SERVER['PHP_SELF']; ?>',
            data: { user_id: user_id, status: status },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload the page to reflect the updated status
                }
            }
        });
    }

</script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
