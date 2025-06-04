<?php
session_start();
include '../conn_db.php';

// Check if the user is authenticated
if (!isset($_SESSION["admin_username"])) {
    // Redirect to the login page if not authenticated
    header("Location: admin-login.php");
    exit();
}

$default_building = isset($_SESSION['default_building']) ? $_SESSION['default_building'] : '';

// Fetch available building names from the 'addbuilding' table
$sql_buildings = "SELECT building_name FROM addbuilding";
$result_buildings = $conn->query($sql_buildings);

$buildings = [];
if ($result_buildings && $result_buildings->num_rows > 0) {
    while ($row = $result_buildings->fetch_assoc()) {
        $buildings[] = $row['building_name'];
    }
}

// Handle building and room selection if provided in the URL
$building_name = isset($_GET['building_name']) ? htmlspecialchars($_GET['building_name']) : $default_building;
$roomNumber = isset($_GET['room']) ? htmlspecialchars($_GET['room']) : 1;

// Select students living in the specified building and room from the 'register' table
$sql_students = $conn->prepare("SELECT * FROM register WHERE building = ? AND room = ?");
$sql_students->bind_param("si", $building_name, $roomNumber);
$sql_students->execute();
$result_students = $sql_students->get_result();

// Calculate the total number of students in the room
$total_students = $result_students->num_rows;

$conn->close();
?>
<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Building Details</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/header.css">
</head>
<body>
<div class="content-wrapper">
  <h3 style="margin-bottom: 25px;">
    <?php
    if (isset($_GET['room'])) {
        echo "និស្សិតស្នាក់នៅអគារ " . htmlspecialchars($building_name) . ", បន្ទប់លេខ " . htmlspecialchars($roomNumber);
    } else {
        echo "Not Found";
    }
    ?>
  </h3>
  <div class="card">
    <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <button type="button" class="btn btn-success btn-shadow" onclick="exportToExcel()">
            <i class="fa-solid fa-file-excel fa-fw"></i> ទាញយក
        </button>
        <button type="button" class="btn btn-warning btn-shadow" onclick="printReport()"><i class="fa-solid fa-print fa-fw"></i>បោះពុម្ព</button>
      </div>
      <div>
        <?php if ($total_students == 8) { ?>
          <span class='badge badge-danger'>បន្ទប់ពេញហើយ!</span>
        <?php } else if ($total_students < 8) { ?>
          <a href="add_student.php?building_name=<?php echo urlencode($building_name); ?>&room=<?php echo urlencode($roomNumber); ?>" class="btn btn-primary"><i class="fa-solid fa-plus fa-fw"></i> បន្ថែមនិស្សិត</a>
        <?php } ?>
      </div>
    </div>
      <div class="table-responsive mt-5">
        <table class="table table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>ល.រ</th>
              <th>លេខសម្គាល់និស្សិត</th>
              <th>ឈ្មោះនិស្សិត</th>
              <th>ភេទ</th>
              <th>ជំនាញ</th>
              <th>កម្រិតសិក្សា</th>
              <th>ឆ្នាំសិក្សា</th>
              <th>បន្ទប់លេខ</th>
              <th>លេខទូរស័ព្ទនិស្សិត</th>
            </tr>
          </thead>
          <tbody>
          <?php
          if ($total_students > 0) {
              $count = 1;
              while ($row = $result_students->fetch_assoc()) {
          ?>
                  <tr>
                      <td><?php echo $count++; ?></td>
                      <td><?php echo htmlspecialchars($row["student_id"]); ?></td>
                      <td><?php echo htmlspecialchars($row["lastname"] . " " . $row["name"]); ?></td>
                      <td><?php echo htmlspecialchars($row["gender"]); ?></td>
                      <td><?php echo htmlspecialchars($row["skill"]); ?></td>
                      <td><?php echo htmlspecialchars($row["education_level"]); ?></td>
                      <td><?php echo htmlspecialchars($row["year"]); ?></td>
                      <td><?php echo htmlspecialchars($row["room"]); ?></td>
                      <td><?php echo htmlspecialchars($row["phone_student"]); ?></td>
                  </tr>
          <?php
              }
          } else {
          ?>
              <tr><td colspan='9' class="text-center">មិនមានទិន្នន័យនិស្សិត!</td></tr>
          <?php
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  function exportToExcel() {
      const buildingName = "<?php echo urlencode($building_name); ?>";
      const roomNumber = "<?php echo urlencode($roomNumber); ?>";
      window.location.href = `export/export_build_number.php?building_name=${buildingName}&room=${roomNumber}`;
  }
  function printReport() {
      const buildingName = "<?php echo urlencode($building_name); ?>";
      const roomNumber = "<?php echo urlencode($roomNumber); ?>";
      const printWindow = window.open(`print_building_details.php?building_name=${buildingName}&room=${roomNumber}`, '_blank');

      // Wait for the content to load and then trigger print
      printWindow.onload = function () {
          printWindow.print();
      };
  }
</script>
</body>
</html>
