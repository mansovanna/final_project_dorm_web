<?php

include '../conn_db.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve staff ID from URL parameter
if(isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    // Construct SQL query to delete staff member
    $sql = "DELETE FROM staff WHERE id = $staff_id";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Deletion successful
        echo "<script>alert('ទិន្ន័យសមាជិកបុគ្គលិកត្រូវបានលុបចេញដោយជោគជ័យ។'); window.location.href = 'data_staff.php';</script>";
    } else {
        // Error occurred
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href = 'data_staff.php';</script>";
    }
} else {
    // ID parameter not provided
    echo "<script>alert('Staff ID not provided.'); window.location.href = 'data_staff.php';</script>";
}

// Close the database connection
$conn->close();
?>
