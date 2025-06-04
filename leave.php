
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

// Include database connection
include 'conn_db.php';

// Retrieve user data from session
$user_id = $_SESSION['user_id'];

// Query to get user's profile information
$query = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

include('include/header_student.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Request</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
         .bg-light {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        a:link{
            text-decoration: none;
        }
     </style>
</head>
<body>
    <div class="container">
        <form action="process_leave.php" method="POST" onsubmit="showPopup()">
            <div class="bg-light p-4">
                <h2 class="h2 text-center">ស្នើរសុំច្បាប់</h2>
                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label>លេខសម្គាល់និស្សិត:</label>
                        <input type="text" name="user_id" value="<?php echo $user['student_id']; ?>" class="form-control" readonly>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>ឈ្មោះនិស្សិត:</label>
                        <input type="text" name="user_name" value="<?php echo $user['lastname']; ?> <?php echo $user['name']; ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label>ចាប់ពីថ្ងៃទី:</label>
                        <input type="date" name="first_date" id="first_date" class="form-control first1" required onchange="calculateDays()">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>ដល់ថ្ងៃទី:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control end2" required onchange="calculateDays()">
                    </div>
                </div>

                <div class="form-group">
                    <label>ចំនួនថ្ងៃ:</label>
                    <input type="text" name="sumday" class="form-control sumday1" readonly>
                </div>
                <div class="form-group">
                    <labe>មូលហេតុ:</labe>
                    <textarea name="reason" class="form-control alaw1" required></textarea>
                </div>
                <div class="form-group text-center">
                    <?php
                        if(isset($_SESSION['MESSAGE'])) {
                            echo '<p>'. $_SESSION['MESSAGE'] . '</p>';
                            unset($_SESSION['MESSAGE']);
                        }
                    ?>
                   <div class="col-12  d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">ស្នើសុំ</button>
                </div>
                </div>
            </div>
        </form>
    </div>
  


    <!-- Bootstrap JS and jQuery (for optional features like dropdowns, tooltips, etc.) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    function calculateDays() {
        // Get the values of the start and end dates
        const firstDate = document.getElementById('first_date').value;
        const endDate = document.getElementById('end_date').value;

        // If both dates are selected, calculate the number of days
        if (firstDate && endDate) {
            const start = new Date(firstDate);
            const end = new Date(endDate);
            
            // Calculate the difference in time
            const timeDifference = end - start;

            // Convert the time difference from milliseconds to days
            const daysDifference = timeDifference / (1000 * 3600 * 24);

            // If the difference is a valid number (non-negative), update the 'sumday' field
            if (!isNaN(daysDifference) && daysDifference >= 0) {
                document.querySelector('.sumday1').value = daysDifference;
            } else {
                document.querySelector('.sumday1').value = ''; // Clear the field if dates are invalid
            }
        }
    }
</script>


    <script>
        function showPopup() {
            alert('ការស្នើសុំរបស់អ្នកទទួលបានជោគជ័យ!');
        }
    </script>
</form>
</body>
</html>
<?php include('include/footer.php'); ?> 
