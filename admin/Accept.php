<?php
include '../conn_db.php'; 

session_start();

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
// Check if the student ID is provided in the URL
if (isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($conn, $_GET['student_id']);
    
    // Retrieve student details from the database
    $sql = "SELECT * FROM register WHERE student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        echo "No student found with ID: " . $student_id;
        exit;
    }
} else {
    echo "Student ID not provided";
    exit;
}
?>
<?php include("../include/header.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="content-wrapper">
        <h4 class="my-4 text-center fw-bold">ផ្លាស់ប្ដូរអគារនិង​ បន្ទប់ សម្រាប់និស្សិត</h4>
        <div class="d-flex justify-content-center">
        <div class="card col-md-10">
            <div class="card-body">
                <h5 style="margin-bottom:40px;">ផ្លាស់ប្ដូរអគារនិង​ បន្ទប់ សម្រាប់និស្សិត: <?= htmlspecialchars($student['name']); ?></h5>
                <form action="update_student_room.php" method="POST">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']); ?>">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label for="building_name">ឈ្មោះអគារ</label>
                            <select id="building_name" name="building_name" class="form-control" required>
                                <option value="">ជ្រើសរើសអគារ</option>
                                <?php
                                // Fetch available buildings from the database
                                $sql_buildings = "SELECT * FROM addbuilding";
                                $result_buildings = $conn->query($sql_buildings);
                                while ($row = $result_buildings->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['building_name']) . "'>" . htmlspecialchars($row['building_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="room_number">បន្ទប់លេខ</label>
                            <select id="room_number" name="room_number" class="form-control" required>
                                <option value="">ជ្រើសរើសបន្ទប់</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">រក្សាទុក</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#building_name').change(function() {
                var building_name = $(this).val();
                if (building_name) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_rooms.php',
                        data: {building_name: building_name},
                        success: function(data) {
                            var rooms = JSON.parse(data);
                            $('#room_number').html('<option value="">ជ្រើសរើសបន្ទប់</option>');
                            $.each(rooms, function(index, value) {
                                $('#room_number').append('<option value="' + value + '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('#room_number').html('<option value="">ជ្រើសរើសបន្ទប់</option>');
                }
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
