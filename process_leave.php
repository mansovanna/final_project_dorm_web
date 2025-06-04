<?php
session_start();
// Connect to the database
include 'conn_db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_date = date("Y-m-d H:i:s");
    // Retrieve form data
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $check_id = mysqli_query($conn, "SELECT student_id FROM register WHERE student_id = '". $user_id . "'");
    $sumday = mysqli_real_escape_string($conn, $_POST['sumday']);
    $first_date = mysqli_real_escape_string($conn, $_POST['first_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $status = 'រង់ចាំ';
    if(mysqli_num_rows($check_id) > 0){
       
        
        // SQL query to insert data into database using prepared statements
        $sql = "INSERT INTO reques_alaw (student_id, user_name, sumday, first_date, end_date, reason, status, re_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $user_id, $user_name, $sumday, $first_date, $end_date, $reason, $status, $request_date);
    
        if ($stmt->execute()) {
            echo '<script>window.location.href = "his_leave.php";</script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }else{
        $_SESSION['MESSAGE'] = "Incorrect student ID.";
        header("location: leave.php");
        exit(0);
    }

    
    $stmt->close();
} 

// Close connection
$conn->close();
?>
