<?php
session_start();

// Include database connection
include 'conn_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['student_id'];
            $_SESSION['img'] = !empty($user['img']) ? $user['img'] : 'img/user1.png';
            $_SESSION['username'] = !empty($user['username']) ? $user['username'] : 'User name';
            header("Location: short.php");
            exit();
        } else {
            // Set password-specific error message
            $_SESSION['password_error'] = "ពាក្យសម្ងាត់មិនត្រឹមត្រូវ។";
        }
    } else {
        // Set ID-specific error message
        $_SESSION['id_error'] = "លេខសម្គាល់មិនត្រឹមត្រូវ។";
    }

    $stmt->close();
    header("Location: login.php"); // Redirect back to the form
    exit();
}

$conn->close();
?>
