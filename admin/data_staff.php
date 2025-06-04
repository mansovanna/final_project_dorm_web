<?php
include '../conn_db.php';
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search parameters
$staff_Name = isset($_GET['staff_Name']) ? $conn->real_escape_string($_GET['staff_Name']) : '';
$phone_number = isset($_GET['phone_number']) ? $conn->real_escape_string($_GET['phone_number']) : '';

// Pagination variables
$results_per_page = isset($_GET['results_per_page']) ? (int)$_GET['results_per_page'] : 10;
// $results_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Build SQL query with search parameters, excluding id = 81
$sql_count = "SELECT COUNT(*) AS total FROM staff WHERE staff_Name LIKE '%$staff_Name%' AND phone_number LIKE '%$phone_number%' AND id != 81";
$result_count = $conn->query($sql_count);
$total = $result_count->fetch_assoc()['total'];
$total_pages = ceil($total / $results_per_page);

$sql = "SELECT * FROM staff WHERE staff_Name LIKE '%$staff_Name%' AND phone_number LIKE '%$phone_number%' AND id != 81 ORDER BY id DESC LIMIT $start_from, $results_per_page";
$result = $conn->query($sql);


?>
<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>staff</title>
    <!-- <link rel="stylesheet" href="../style/style_ds.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
    <div class="content-wrapper">
         <h3 style="font-weight: bold; margin-bottom: 20px;">ទិន្នន័យបុគ្គលិក</h3>
            <div class="card card-primary card-outline">
                <div class="card-body">
                <?php if ($_SESSION['admin_username'] == 'admin'): ?>
                    <div class="row mb-3">
                        <div class="col d-flex align-items-center">
                            <div class="ml-auto mt-2">
                                <a href="add_staff.php" class="btn btn-primary">
                                    <i class="fa-solid fa-plus fa-fw"></i> បន្ថែមបុគ្គលិក
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                    <form method="GET" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="staff_Name">ឈ្មោះ</label>
                                <input type="text" id="staff_Name" name="staff_Name" class="form-control" placeholder="ស្វែងរកឈ្មោះបុគ្គលិក..." value="<?php echo htmlspecialchars($staff_Name); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number">លេខទូរស័ព្ទ</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="ស្វែងរកលេខទំនាក់ទំនង..." value="<?php echo htmlspecialchars($phone_number); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                            <a href="data_staff.php" type="button" class="btn btn-danger btn-shadow">សម្អាត</a>
                            <!-- <button type="button" class="btn btn-success btn-shadow">ទាញយក</button> -->
                            <button type="button" class="btn btn-success btn-shadow" onclick="window.location.href='export/export_staff.php?staff_Name=<?php echo $staff_Name; ?>&phone_number=<?php echo $phone_number; ?>'">ទាញយក</button>

                        </div>
                    </form>

            <!-- <div class="container mt-5"> -->
       
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>រូបភាពបុគ្គលិក</th>
                        <th>ឈ្មោះបុគ្គលិក</th>
                        <th>អក្សរឡាតាំង</th>
                        <th>លេខទូរស័ព្ទ</th>
                        <th>សារអេឡិចត្រូនិក</th>
                        <?php if ($_SESSION['admin_username'] == 'admin'): ?>
                        <th style="width: 9%;">សកម្មភាព</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if ($result->num_rows > 0) {
                    $i = $start_from + 1;
                        while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td class="text-center"><img src="<?php echo $row['img']; ?>" alt="Staff Picture" style="width: 50px; height: 50px;"></td>
                    <td><?php echo $row['staff_Name']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['phone_number']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <?php if ($_SESSION['admin_username'] == 'admin'): ?>
                    <td>
                        <a href="edit_data_staff.php?id=<?php echo $row['id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)"><i class="fa-regular fa-trash-can"></i></button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php
                $i++;
            }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">មិនមានទិន្នន័យបុគ្គលិក!</td>
                    </tr>
                    <?php
                }
                $conn->close();
                ?>
                </tbody>
            </table>
        </div>
        
                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($page > 1){ echo "?page=" . ($page - 1) . "&staff_Name=" . urlencode($staff_Name) . "&phone_number=" . urlencode($phone_number). "&results_per_page=" . urlencode($results_per_page); } ?>"><i class="fa-solid fa-angles-left"></i></a>
                        </li>

                        <!-- Page Numbers -->
                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if($page == $i) {echo 'active';} ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&staff_Name=<?= urlencode($staff_Name); ?>&phone_number=<?= urlencode($phone_number); ?>&results_per_page=<?= urlencode($results_per_page); ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Next Page Link -->
                        <li class="page-item <?php if($page >= $total_pages) { echo 'disabled'; } ?>">
                            <a class="page-link" href="<?php if($page < $total_pages) { echo "?page=" . ($page + 1) . "&staff_Name=" . urlencode($staff_Name) . "&phone_number=" . urlencode($phone_number) . "&results_per_page=" . urlencode($results_per_page);} ?>">Next</a>
                        </li>
                    </ul>
                </nav>
                <!-- End Pagination -->
                
                <!-- Pagination Limit -->
                <form method="GET" action="" class="form-inline" style="margin-top: -55px;">
                    <input type="hidden" name="staff_Name" value="<?php echo htmlspecialchars($staff_Name); ?>">
                    <input type="hidden" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>">
                    <input type="hidden" name="page" value="<?php echo htmlspecialchars($page); ?>">
                    <div class="form-group limit">
                        <label for="results_per_page" class="mr-2">កំណត់ត្រាទំព័រ:</label>
                        <select class="form-control" name="results_per_page" id="results_per_page" onchange="this.form.submit()">
                            <option value="5" <?php if ($results_per_page == 5) echo 'selected'; ?>>5</option>
                            <option value="10" <?php if ($results_per_page == 10) echo 'selected'; ?>>10</option>
                            <option value="15" <?php if ($results_per_page == 15) echo 'selected'; ?>>15</option>
                            <option value="20" <?php if ($results_per_page == 20) echo 'selected'; ?>>20</option>
                        </select>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            if (confirm("តើអ្នកប្រាកដថាចង់លុបទិន្នន័យបុគ្គលិកនេះចេញមែនទេ?")) {
                window.location.href = 'remove_staff.php?id=' + id;
            }
        }
    </script>
</body>
</html>
