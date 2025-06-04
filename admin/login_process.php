<?php
session_start();
require_once '../conn_db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Clear previous session errors
    unset($_SESSION['username_error']);
    unset($_SESSION['password_error']);

    // Validate inputs
    if (empty($username)) {
        $_SESSION['username_error'] = "សូមបញ្ចូលឈ្មោះអ្នកប្រើ";
    }
    if (empty($password)) {
        $_SESSION['password_error'] = "សូមបញ្ចូលពាក្យសម្ងាត់";
    }

    if (!empty($username) && !empty($password)) {
        // Check credentials
        $stmt = $conn->prepare("SELECT * FROM staff WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $admin['password'])) {
                // Store session data
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['img'] = !empty($admin['img']) ? $admin['img'] : 'user1.png';
                header("Location: das_admin.php");
                exit();
            } else {
                $_SESSION['password_error'] = "ពាក្យសម្ងាត់មិនត្រឹមត្រូវ";
            }
        } else {
            $_SESSION['username_error'] = "រកមិនឃើញអ្នកប្រើ";
        }
    }
    header("Location: login.php"); // Redirect back to login form
    exit();
} else {
    echo "Invalid request method.";
}
?>
