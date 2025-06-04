<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Handle form submission for adding a new building
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_building'])) {
    $building_name = mysqli_real_escape_string($conn, $_POST['building_name']);
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);

    // SQL query to insert data into database using prepared statements
    $sql = "INSERT INTO addbuilding (building_name, room_number) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $building_name, $room_number);

    if ($stmt->execute()) {
        // Redirect to the same page after successful submission to avoid re-insertion on refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

// Handle deletion request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_building'])) {
    $building_id = mysqli_real_escape_string($conn, $_POST['building_id']);

    // SQL query to delete data from database using prepared statements
    $sql = "DELETE FROM addbuilding WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $building_id);

    if ($stmt->execute()) {
        // Redirect to the same page after successful deletion to refresh the list
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

// Fetch data from the database with optional search filter
$search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';
$sql = "SELECT * FROM addbuilding WHERE building_name LIKE ?";

$stmt = $conn->prepare($sql);
$search_term = '%' . $search . '%';
$stmt->bind_param("s", $search_term);
$stmt->execute();
$result = $stmt->get_result();

// Close connection
$stmt->close();
$conn->close();
?>

<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Building</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/header.css">
  <link rel="stylesheet" href="../style/font_style.css">
  <style>
      .card-container {
          display: flex;
          flex-wrap: wrap;
          gap: 20px;
      }

      .card {
          background: #ffffff;
          border: 1px solid #ddd;
          border-radius: 5px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          width: 100%;
          transition: transform 0.3s, box-shadow 0.3s;
          overflow: hidden;
          position: relative;
      }

      .card-header {
          background: #f5f5f5;
          padding: 15px;
          border-bottom: 1px solid #ddd;
      }

      .card-body {
          padding: 20px;
      }

      .card-body a {
        text-decoration: none;
      }

      .card-footer {
          background: #f5f5f5;
          padding: 15px;
          border-top: 1px solid #ddd;
          text-align: right;
      }

      .popup {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.5);
          display: none;
          align-items: center;
          justify-content: center;
      }

      .popup.show {
          display: flex;
      }

      .popup-content {
          background: #ffffff;
          border-radius: 8px;
          padding: 20px;
          width: 80%;
          max-width: 500px;
          position: relative;
      }

      .popup-content .close {
          position: absolute;
          top: 10px;
          right: 10px;
          font-size: 24px;
          cursor: pointer;
         
      }

      .card-col {
          flex: 1 0 21%; /* Adjust for spacing */
          margin-bottom: 20px;
      }

      @media (max-width: 1200px) {
          .card-col {
              flex: 1 0 24%; /* Adjust for medium screens */
          }
          .col-md-6 .find_build{
            margin-bottom: 20px;
          }
      }

      @media (max-width: 992px) {
          .card-col {
              flex: 1 0 48%; /* Adjust for tablets */
          }
          .col-md-6 .find_build{
            margin-bottom: 20px;
          }
      }

      @media (max-width: 576px) {
          .card-col {
              flex: 1 0 100%; /* Full width for mobile */
              
          }
          .col-md-6 .find_build{
            margin-bottom: 20px;
          }
      }
  </style>
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">បញ្ជីអគារស្នាក់នៅ</h3>
    <div class="card card-primary card-outline">
        
        <div class="card-body">
        <form action="" method="POST">
            <div class="row mb-3">
               
                    <div class="col-md-6">
                            <input type="text" name="search" class="form-control find_build" placeholder="ស្វែងរកឈ្មោះអគារ" value="<?= htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">ស្វែងរក</button>
                        <a href="build.php" type="button" class="btn btn-danger btn-shadow">សម្អាត</a>
                    </div>
                </form> 
            </div>

            <div class="row">
                <div class="col-12 mb-3">
                    <a href="#" class="btn btn-primary" onclick="togglePopup()"><i class="fa-solid fa-plus fa-fw"></i>បន្ថែមអគារ</a>
                </div>
            </div>

            <div class="card-container row">
                <?php
                $i = 1;
                while ($row = $result->fetch_assoc()) :
                ?>
                <div class="card-col col-lg-3 col-md-4 col-sm-6">
                    <div class="card">
                        <a href="buil_list.php?id=<?= $row['id']; ?>">
                            <div class="card-header">
                                <h5 style="color: #333; height: 6vh;"><strong>អគារស្នាក់នៅ:</strong> <?= $row['building_name']; ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>ចំនួនបន្ទប់:</strong> <?= $row['room_number']; ?></p>
                            </div>
                        </a>
                        <div class="card-footer">
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="building_id" value="<?= $row['id']; ?>">
                                <button type="submit" name="delete_building" class="btn btn-light"><i class="fa-regular fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="popup" id="roomPopup">
                <div class="popup-content">
                    <span class="close" onclick="togglePopup()">&times;</span>
                    <h2>បន្ថែមអគារ</h2>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="building_name">ឈ្មោះអគារ</label>
                            <input type="text" id="building_name" name="building_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="room_number">ចំនួនបន្ទប់</label>
                            <input type="number" id="room_number" name="room_number" class="form-control" required>
                        </div>
                        <button type="submit" name="add_building" class="btn btn-primary">បន្ថែម</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePopup() {
        var popup = document.getElementById("roomPopup");
        popup.classList.toggle("show");
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
