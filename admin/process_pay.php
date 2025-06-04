<?php
session_start();

// Connect to the database
include '../conn_db.php';

// Check if the user is not authenticated
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $year = $_POST['year'];
    // Check if payment_id is provided for update
    $payment_id = isset($_POST['payment_id']) ? (int) $_POST['payment_id'] : null;

    // ✅ Optional: You can derive user_name from session or a query if needed
    $user_name = $_SESSION["admin_username"]; // Or set to something else

    $accommodation_fee = isset($_POST['accommodation_fee']) && is_numeric($_POST['accommodation_fee']) ? (float) $_POST['accommodation_fee'] : 0.0;
    $discount = isset($_POST['discount']) && is_numeric($_POST['discount']) ? (float) $_POST['discount'] : 0.0;
    $water_fee = isset($_POST['water_fee']) && is_numeric($_POST['water_fee']) ? (float) $_POST['water_fee'] : 0.0;
    $electricity_fee = isset($_POST['electricity_fee']) && is_numeric($_POST['electricity_fee']) ? (float) $_POST['electricity_fee'] : 0.0;

    // check if all required fields are filled
    if (empty($student_id) || empty($year)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }
    // Check if student_id exists in register table
    $check_query = "SELECT student_id FROM register WHERE student_id = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("s", $student_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows == 0) {
        echo "<script>alert('Invalid Student ID. Please enter a valid student ID.'); window.history.back();</script>";
    } else {
        $total_fee = ($accommodation_fee + $water_fee + $electricity_fee) - $discount;
        $status = "Approved";

        if ($payment_id !== null) {
            // Update existing payment
            $stmt = $conn->prepare("UPDATE payment SET
            student_id = ?, user_name = ?, building = ?, room_number = ?,
            accommodation_fee = ?, discount = ?, water_fee = ?,
            electricity_fee = ?, total_fee = ?, status = ?, date = ?
            WHERE id = ?");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param(
                "ssssddddsssi",
                $student_id,
                $user_name,
                $building,
                $room_number,
                $accommodation_fee,
                $discount,
                $water_fee,
                $electricity_fee,
                $total_fee,
                $status,
                $year,
                $payment_id
            );
        } else {
            // Insert new payment
            $stmt = $conn->prepare("INSERT INTO payment (
            student_id, user_name, building, room_number,
            accommodation_fee, discount, water_fee,
            electricity_fee, total_fee, status, date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param(
                "ssssddddsss",
                $student_id,
                $user_name,
                $building,
                $room_number,
                $accommodation_fee,
                $discount,
                $water_fee,
                $electricity_fee,
                $total_fee,
                $status,
                $year
            );
        }

        if ($stmt->execute()) {
            // echo '<script>window.location.href = "no_pay.php";</script>';
            echo "<script>alert('Payment processed successfully.'); window.location.href = 'no_pay.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();
    $conn->close();
}

echo "Error: {$stmt->error}";
