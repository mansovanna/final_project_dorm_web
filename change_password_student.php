<?php
session_start();
include 'conn_db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Check if the new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo '<script>alert("ពាក្យសម្ងាត់ថ្មី និងការបញ្ជាក់មិនដូចគ្នា។"); window.history.back();</script>';
        exit;
    }

    // Fetch the current hashed password from the database
    $sql = "SELECT password FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (!password_verify($current_password, $hashed_password)) {
        echo '<script>alert("ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវ។"); window.history.back();</script>';
        exit;
    }

    // Hash the new password
    $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_sql = "UPDATE register SET password = ? WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $new_hashed_password, $student_id);

    if ($update_stmt->execute()) {
        echo "<script>
                alert('ពាក្យសម្ងាត់បានផ្លាស់ប្តូរដោយជោគជ័យ!');
                window.location.href = 'show_pf.php'; 
              </script>";
    } else {
        echo "<script>
                alert('មានបញ្ហាក្នុងការផ្លាស់ប្តូរពាក្យសម្ងាត់: " . $update_stmt->error . "');
                window.history.back();
              </script>";
    }

    $update_stmt->close();
    $conn->close();
}
?>
