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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the button is clicked and update the status accordingly
    if (isset($_POST['status']) && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $newStatus = ($_POST['status'] == 'អនុញ្ញាត') ? 'អនុញ្ញាត' : 'មិនអនុញ្ញាត';

        $sql = "UPDATE register SET status = '$newStatus' WHERE student_id = '$student_id'";
        if (!$conn->query($sql) === TRUE) {
            die('Failed to update status.');
        }
    }
}

// Search parameters
$skill = isset($_GET['skill']) ? $conn->real_escape_string($_GET['skill']) : '';
$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';
$student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';

// Build the SQL query with search parameters
$conditions = ["status = 'រង់ចាំ'"];
if ($skill) {
    $conditions[] = "skill = '$skill'";
}
if ($year) {
    $conditions[] = "year = '$year'";
}
if ($student_id) {
    $conditions[] = "student_id LIKE '%$student_id%'";
}
$sql_select = "SELECT * FROM register WHERE " . implode(" AND ", $conditions);

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Add limit and offset for pagination
$sql_select .= " LIMIT $start, $limit";

// Get total number of rows for pagination
$sql_count = "SELECT COUNT(*) AS total FROM register WHERE " . implode(" AND ", $conditions);
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
    <!-- <link rel="stylesheet" href="../style/rg_admin.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">និស្សិតចុះឈ្មោះ</h3>
            <div class="card card-primary card-outline">
                <div class="card-body">  
                    <form method="GET" action="">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>លេខសម្គាល់និស្សិត</label>
                                <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo isset($_GET['student_id']) ? $_GET['student_id'] : ''; ?>">
                            </div>
                            <div class="col-md-4">
                                <label>ជំនាញ</label>
                                <select name="skill" id="skill" class="form-control">
                                    <option value="" <?php echo isset($_GET['skill']) && $_GET['skill'] == '' ? 'selected' : ''; ?>>ជ្រើសរើសជំនាញ</option>
                                    <option value="បច្ចេកវិទ្យាកុំព្យូទ័រ" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'បច្ចេកវិទ្យាកុំព្យូទ័រ' ? 'selected' : ''; ?>>បច្ចេកវិទ្យាកុំព្យូទ័រ</option>
                                    <option value="វិទ្យាសាស្ត្រដំណាំ" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'វិទ្យាសាស្ត្រដំណាំ' ? 'selected' : ''; ?>>វិទ្យាសាស្ត្រដំណាំ</option>
                                    <option value="គីមីចំណីអាហារ" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'គីមីចំណីអាហារ' ? 'selected' : ''; ?>>គីមីចំណីអាហារ</option>
                                    <option value="បច្ចេកវិទ្យាអគ្គីសនី" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'បច្ចេកវិទ្យាអគ្គីសនី' ? 'selected' : ''; ?>>បច្ចេកវិទ្យាអគ្គីសនី</option>
                                    <option value="មេកានិច" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'មេកានិច' ? 'selected' : ''; ?>>មេកានិច</option>
                                    <option value="វិទ្យាសាស្ត្រសត្វ" <?php echo isset($_GET['skill']) && $_GET['skill'] == 'វិទ្យាសាស្ត្រសត្វ' ? 'selected' : ''; ?>>វិទ្យាសាស្ត្រសត្វ</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>ឆ្នាំសិក្សា</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="" <?php echo isset($_GET['year']) && $_GET['year'] == '' ? 'selected' : ''; ?>>ឆ្នាំ</option>
                                    <option value="1" <?php echo isset($_GET['year']) && $_GET['year'] == '1' ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo isset($_GET['year']) && $_GET['year'] == '2' ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo isset($_GET['year']) && $_GET['year'] == '3' ? 'selected' : ''; ?>>3</option>
                                    <option value="4" <?php echo isset($_GET['year']) && $_GET['year'] == '4' ? 'selected' : ''; ?>>4</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-3 d-flex align-items-end" style="display: flex;gap: 5px;align-items: flex-end; ">
                                <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                                <a href="rg_admin.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                                <button class="btn btn-success btn-shadow" onclick="exportToExcel()">ទាញយក</button>
                            </div>   -->
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                            <a href="rg_admin.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                            <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_rgadmin.php?skill=<?php echo $skill; ?>&year=<?php echo $year; ?>&student_id=<?php echo $student_id; ?>'">ទាញយក</button>
                        </div>  
                         
                    </form> 
                    
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead">
                <tr>
                    <th>ល.រ</th>
                    <th>លេខសម្គាល់និស្សិត</th>
                    <th>ឈ្មោះនិស្សិត</th>
                    <th>ភេទ</th>
                    <th>ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th>អាសយដ្ឋាន</th>
                    <th>​លេខទូរស័ព្ទនិស្សិត</th>
                    <th>ជំនាញ</th>
                    <th>កម្រិតសិក្សា</th>
                    <th>ឆ្នាំសិក្សា</th>
                    <th >សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    $i = $start + 1;
                    while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row["student_id"]; ?></td>
                <td><?php echo $row["lastname"] . " " . $row["name"]; ?></td>
                <td><?php echo $row["gender"]; ?></td>
                <td><?php echo $row["dob"]; ?></td>
                <td><?php echo $row["address"]; ?></td>
                <td><?php echo $row["phone_student"]; ?></td>
                <td><?php echo $row["skill"]; ?></td>
                <td><?php echo $row["education_level"]; ?></td>
                <td><?php echo $row["year"]; ?></td>
                <td>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($row['student_id']); ?>">
                        
                        <?php if ($row['status'] == 'អនុញ្ញាត') { ?>
                            <button class="btn btn-primary" style="margin-right: 5px;" disabled>បានអនុញ្ញាត</button>
                        <?php } elseif ($row['status'] == 'មិនអនុញ្ញាត') { ?>
                            <button class="btn btn-danger" style="margin-right: 5px;" disabled>មិនអនុញ្ញាត</button>
                        <?php } else { ?>
                            <button class="btn btn-success" style="margin-right: 5px;" type="submit" name="status" value="អនុញ្ញាត"><i class="fa-solid fa-circle-check"></i></button>
                            <button class="btn btn-danger" style="margin-right: 5px;" type="submit" name="status" value="មិនអនុញ្ញាត"><i class="fa-solid fa-rectangle-xmark"></i></button>
                        <?php } ?>
                    </form>
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
</body>
</html>

