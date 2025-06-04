<?php
// Start session
session_start();

// Include database connection
include '../conn_db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // Check if the new password and confirmation match
    if ($new_password !== $confirm_new_password) {
        echo '<script>alert("ពាក្យសម្ងាត់ថ្មី និងការបញ្ជាក់មិនដូចគ្នា។"); window.history.back();</script>';
        exit();
    }

    // Query to get the current password from the database
    $query = "SELECT password FROM staff WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify the current password
        if (password_verify($current_password, $user['password'])) {
            // Hash the new password
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update the password in the database
            $update_query = "UPDATE staff SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("si", $new_password_hashed, $user_id);
            
            if ($update_stmt->execute()) {
                // Display success popup and redirect
                echo '<script>alert("ពាក្យសម្ងាត់ត្រូវបានប្តូរដោយជោគជ័យ!"); window.location.href = "pf_staff.php";</script>';
            } else {
                // Error while updating password
                echo '<script>alert("មានបញ្ហាក្នុងការប្តូរពាក្យសម្ងាត់។"); window.history.back();</script>';
            }
        } else {
            // Incorrect current password
            echo '<script>alert("ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវ។"); window.history.back();</script>';
        }
    } else {
        // User not found
        echo '<script>alert("មិនមានអ្នកប្រើប្រាស់ទេ។"); window.history.back();</script>';
    }
}
?>
