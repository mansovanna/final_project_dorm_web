<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

// Delete QR code
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['qr_id'])) {
    $qr_id = (int) $_POST['qr_id'];

    // Get the image filename to delete the actual file
    $result = $conn->query("SELECT image_url FROM qr_code_bank WHERE id = $qr_id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = "../uploads/images_qr/" . $row['image_url'];

        // Delete the image file
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the record from the database
        $conn->query("DELETE FROM qr_code_bank WHERE id = $qr_id");

        // Redirect back to refresh the page
        header("Location: qr-code-bank.php");
        exit();
    } else {
        echo "QR code not found.";
    }
}


?>