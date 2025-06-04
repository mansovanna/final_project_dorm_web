<?php
session_start();

// Connect to the database
include 'conn_db.php';

// Check if the user is not authenticated
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];

    // ✅ Explicitly cast POST values to float to avoid type error
    $accommodation_fee = isset($_POST['accommodation_fee']) ? floatval($_POST['accommodation_fee']) : 0.0;
    $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0.0;
    $water_fee = isset($_POST['water_fee']) ? floatval($_POST['water_fee']) : 0.0;
    $electricity_fee = isset($_POST['electricity_fee']) ? floatval($_POST['electricity_fee']) : 0.0;

    // Check if student_id exists in register table
    $check_query = "SELECT student_id FROM register WHERE student_id = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $student_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows === 0) {
        echo "<script>alert('Invalid Student ID. Please enter a valid student ID.'); window.history.back();</script>";
    } else {
        // ✅ Perform calculation with float variables only
        $total_fee = ($accommodation_fee + $water_fee + $electricity_fee) - $discount;

        // Insert data using prepared statements
        $stmt = $conn->prepare("
            INSERT INTO payment (
                student_id, building, room_number,
                accommodation_fee, discount,
                water_fee, electricity_fee, total_fee
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssddddd",
            $student_id,
            $building,
            $room_number,
            $accommodation_fee,
            $discount,
            $water_fee,
            $electricity_fee,
            $total_fee
        );

        if ($stmt->execute()) {
            echo '<script>window.location.href = "his_pay.php";</script>';
        } else {
            echo "Error inserting payment: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}
?>
