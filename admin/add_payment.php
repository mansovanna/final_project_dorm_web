<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Get form data
$room = $_POST['room']; // ✅ Use consistent variable name
$electricity = $_POST['electricity_fee'];
$water = $_POST['water_fee'];
$discount = $_POST['discount'];
$total = $_POST['total'];

// Check if there's existing data
$checkSql = "SELECT id FROM payment_summary LIMIT 1";
$result = mysqli_query($conn, $checkSql);

if (mysqli_num_rows($result) > 0) {
    // Update existing record
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];

    $updateSql = "UPDATE payment_summary SET 
                    electricity_fee = '$electricity',
                    water_fee = '$water',
                    discount = '$discount',
                    total = '$total',
                    room = '$room'
                  WHERE id = '$id'"; // ✅ Removed trailing comma

    if (mysqli_query($conn, $updateSql)) {
        header("Location: no_pay.php");
        exit();
    } else {
        echo "Error updating payment: " . mysqli_error($conn);
    }

} else {
    // Insert new record
    $insertSql = "INSERT INTO payment_summary (electricity_fee, water_fee, discount, total, room)
                  VALUES ('$electricity', '$water', '$discount', '$total', '$room')";

    if (mysqli_query($conn, $insertSql)) {
        header("Location: no_pay.php");
        exit();
    } else {
        echo "Error inserting payment: " . mysqli_error($conn);
    }
}
?>
