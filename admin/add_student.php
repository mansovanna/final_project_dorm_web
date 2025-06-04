<?php
session_start();

include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the button is clicked and update the status accordingly
    if (isset($_POST['status']) && isset($_POST['student_id'])) {
        $student_id = $_POST['student_id'];
        $newStatus = ($_POST['status'] == 'អនុញ្ញាត') ? 'អនុញ្ញាត' : 'មិនអនុញ្ញាត';

        // Update the status in the database or your data source
        $sql = "UPDATE register SET status = '$newStatus' WHERE student_id = '$student_id'";
        if (!$conn->query($sql) === TRUE) {
            die('Failed to update status.');
        }
    }

    // Handle add to building and room
    if (isset($_POST['add_to_building']) && isset($_POST['student_id']) && isset($_POST['building']) && isset($_POST['room'])) {
        $student_id = $_POST['student_id'];
        $building = $_POST['building']; 
        $room = $_POST['room'];

        $currentDate = date("Y-m-d");
        // Update the building and room information in the database
        $sql = "UPDATE register SET building = '$building', room = '$room' , stay = '$currentDate' WHERE student_id = '$student_id'";
        if (!$conn->query($sql) === TRUE) {
            die('Failed to update building and room.');
        }
    }
}



// Search variables
$skill = isset($_GET['skill']) ? $_GET['skill'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$year = isset($_GET['year']) ? $conn->real_escape_string($_GET['year']) : '';
$student_id = isset($_GET['student_id']) ? $conn->real_escape_string($_GET['student_id']) : '';

// Build the query with search criteria and check for empty building and room
$sql_select = "SELECT DISTINCT * FROM register WHERE status = 'អនុញ្ញាត' AND (building IS NULL OR building = '') AND (room IS NULL OR room = '')";
if ($skill != '') {
    $sql_select .= " AND skill LIKE '%$skill%'";
}
if ($gender != '') {
    $sql_select .= " AND gender = '$gender'";
}
if ($year) {
    $sql_select .= " AND year = '$year'";
}
if ($student_id) {
    $sql_select .= " AND student_id LIKE '%$student_id%'";
}
$sql_select .= " ORDER BY user_id DESC";
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
    <style>
        body{
            font-family: 'khmer os siemreap';
        }
    </style>
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">និស្សិតចុះឈ្មោះ</h3>
            <div class="card card-primary card-outline">
                <div class="card-body">  
                    <form method="GET" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <label>ឆ្នាំសិក្សា</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="" <?php echo isset($_GET['year']) && $_GET['year'] == '' ? 'selected' : ''; ?>>ឆ្នាំ</option>
                                    <option value="1" <?php echo isset($_GET['year']) && $_GET['year'] == '1' ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo isset($_GET['year']) && $_GET['year'] == '2' ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo isset($_GET['year']) && $_GET['year'] == '3' ? 'selected' : ''; ?>>3</option>
                                    <option value="4" <?php echo isset($_GET['year']) && $_GET['year'] == '4' ? 'selected' : ''; ?>>4</option>
                                </select>
                            </div>
                        
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>លេខសម្គាល់និស្សិត</label>
                                <input type="text" name="student_id" class="form-control" placeholder="Student ID" value="<?php echo isset($_GET['student_id']) ? $_GET['student_id'] : ''; ?>">
                            </div>
                            <div class="col-md-6">
                            <label for="gender">ភេទ:</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="" <?php echo ($gender == '') ? 'selected' : ''; ?>>All</option>
                                    <option value="ប្រុស" <?php echo ($gender == 'ប្រុស') ? 'selected' : ''; ?>>ប្រុស</option>
                                    <option value="ស្រី" <?php echo ($gender == 'ស្រី') ? 'selected' : ''; ?>>ស្រី</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-shadow">ស្វែងរក</button>
                            <a href="add_student.php" class="btn btn-danger btn-shadow">សម្អាត</a>
                        </div>   
                    </form> 
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead class="thead">
            <tr id="table" style="font-size: 16px;">
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
                <th colspan="2">សកម្មភាព</th>
            </tr>
            </thead>
            <?php
            if ($result->num_rows > 0) {
                $i = 1 ;
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
                            <form method='post' style='display: inline;'>
                                <input type='hidden' name='student_id' value='<?php echo $row['student_id']; ?>'>
                                <?php if ($row['status'] == 'មិនអនុញ្ញាត') { ?>
                                    <button class='btn btn-danger' style='margin-right: 5px;' disabled>មិនអនុញ្ញាត</button>
                                <?php } ?>
                            </form>
                       
                            <form method='post' style='display: inline;'>
                                <input type='hidden' name='student_id' value='<?php echo $row['student_id']; ?>'>
                                <input type='hidden' name='building' value='<?php echo isset($_GET['building_name']) ? htmlspecialchars($_GET['building_name']) : ''; ?>'>
                                <input type='hidden' name='room' value='<?php echo isset($_GET['room']) ? htmlspecialchars($_GET['room']) : ''; ?>'>
                                <input type='hidden' name='add_to_building' value='true'>
                                <button class='btn btn-primary' type='submit'><i class="fa-solid fa-plus"></i></button>
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                ?>
                <tr><td colspan='12'>No data available</td></tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>
