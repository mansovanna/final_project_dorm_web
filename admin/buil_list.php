<?php
include '../conn_db.php'; 
session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
// Check if building ID is provided in the URL
if (isset($_GET['id'])) {
    $building_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Retrieve building details from the database
    $sql = "SELECT * FROM addbuilding WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $building_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $building_name = $row['building_name'];
        $room_number = $row['room_number']; // Added this variable
    } else {
        echo "No building found with ID: " . htmlspecialchars($building_id);
        exit;
    }
} else {
    echo "Building ID not provided";
    exit;
}
?>

<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="../style/header.css">
  <title>Building List</title>
  <style>
    th,td{
      text-align: center;
    }
  </style>
</head>

<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold;">បញ្ជីអគារ <?php echo htmlspecialchars($building_name); ?></h3>
    <div class="ml-auto mt-2" style="margin-bottom: 20px;">
      <a href="build.php" class="btn btn-secondary"><i class="fas fa-share fa-flip-horizontal fa-fw"></i> ត្រឡប់</a>
    </div>
    <!-- <div class="card card-primary card-outline">
        <div class="card-body">  -->
      <div class="table-responsive">
      <table class="table table-bordered table-hover table-striped">
          <thead class="thead">
            <tr>
              <th>ល.រ</th>
              <th>ឈ្មោះអគារ</th>
              <th>បន្ទប់លេខ</th>
              <th>ចំនួននិស្សិត</th>
              <th>ស្ថានភាព</th>
              <th width="6%;">លម្អិត</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Determine range of rooms based on your logic
            if ($room_number == 10) {
                $rooms = range(1, 10);
            } else {
                $rooms = range(1, $room_number);
            }
            
            foreach ($rooms as $index => $room) {
                // Select total number of students in the room from the 'register' table
                $sql_total_room_register = "SELECT COUNT(*) AS total_room_register FROM register WHERE building = ? AND room = ?";
                $stmt_total_room_register = $conn->prepare($sql_total_room_register);
                $stmt_total_room_register->bind_param("si", $building_name, $room);
                $stmt_total_room_register->execute();
                $result_total_room_register = $stmt_total_room_register->get_result();
                $row_total_room_register = $result_total_room_register->fetch_assoc();
                $total_students = $row_total_room_register["total_room_register"];
        ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td>អគារ <?php echo htmlspecialchars($building_name); ?></td>
            <td><?php echo htmlspecialchars($room); ?></td>
            <td><?php echo htmlspecialchars($total_students); ?></td>
            <td>
              <?php
                if ($total_students == 8) { 
              ?>
                  <span class='status full' style="background: #dc3545; padding: 4px; color:#fff;">បន្ទប់ពេញហើយ!</span>
              <?php
                } else if ($total_students < 8) {
              ?>
                  <span class='status available' style="background: #28a745; padding: 4px; color:#fff;">បន្ទប់នៅទំនេរ!</span>
              <?php
                } else if ($total_students > 8) {
              ?>
                  <span class='status available' style="background: #e3b912;  padding: 4px;">បន្ទប់លើស!</span>
              <?php
                }
              ?>
            </td>
            <td>
            <!-- <a href="#" class="btn btn-primary" style="background-color: rgb(40, 96, 199)"><i class="fas fa-edit"></i></a> -->
            <a href="build_number.php?building_name=<?php echo urlencode($building_name); ?>&room=<?php echo urlencode($room); ?>" class="btn btn-info"><i class="fa-regular fa-eye"></i></a>
            </td>
        </tr>
        <?php
            }
            $conn->close();
        ?>
        </tbody>
      </table>
    <!-- </div>
  </div> -->
</body>
</html>
