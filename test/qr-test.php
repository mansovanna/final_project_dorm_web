<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

$qr_codes = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qr_code"])) {
    $targetDir = "../uploads/images_qr/";

    // Automatically create the folder if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Step 1: Get the current QR code from DB
    $getOld = "SELECT * FROM qr_code_bank ORDER BY id DESC LIMIT 1";
    $oldResult = $conn->query($getOld);
    $oldImagePath = null;
    if ($oldResult && $oldResult->num_rows > 0) {
        $oldData = $oldResult->fetch_assoc();
        $oldImagePath = "../" . $oldData["image_url"];
    }

    // Step 2: Prepare new file
    $fileName = time() . '_' . basename($_FILES["qr_code"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Step 3: Upload new QR code
    if (in_array($fileType, ["jpg", "jpeg", "png"])) {
        if (move_uploaded_file($_FILES["qr_code"]["tmp_name"], $targetFile)) {

            // Step 4: Delete old image file if exists
            if ($oldImagePath && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Step 5: Delete old DB record
            $conn->query("DELETE FROM qr_code_bank");

            // Step 6: Insert new QR code info
            $relativePath = "uploads/images_qr/" . $fileName;
            $sql = "INSERT INTO qr_code_bank (image_url) VALUES ('$relativePath')";

            if ($conn->query($sql) === TRUE) {
                header("Location: qr-code-bank.php");
                exit();
            } else {
                echo "❌ Database error: " . $conn->error;
            }
        } else {
            echo "❌ Failed to upload file.<br>";
            echo "Temp file: " . $_FILES["qr_code"]["tmp_name"] . "<br>";
            echo "Target: " . $targetFile . "<br>";
            echo "Error Code: " . $_FILES["qr_code"]["error"];
        }
    } else {
        echo "❌ Only JPG, JPEG, PNG files are allowed.";
    }
}


// Get the latest QR code
$get_data = "SELECT * FROM qr_code_bank ORDER BY id DESC LIMIT 1";
$result_qr = $conn->query($get_data);
if ($result_qr && $result_qr->num_rows > 0) {
    $qr_codes = $result_qr->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>

<body>
    <?php include("../include/header.php"); ?>
    <div class="content-wrapper p-4">
        <h3 class="font-weight-bold mb-4">KH QR Code សម្រាប់បង់លុយ</h3>
        <div class="card card-primary card-outline">
            <div class="card-body d-flex align-items-start flex-wrap gap-2" style="gap: 1rem;">

                <!-- Show QR Code -->
                <?php if (!empty($qr_codes)): ?>
                    <div class="mb-4">
                        <label class="font-weight-bold">QR Code បច្ចុប្បន្ន:</label><br>
                        <img src="<?= htmlspecialchars('../' . $qr_codes['image_url']) ?>" alt="QR Code"
                            class="img-thumbnail" style="max-width: 300px;">

                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">មិនទាន់មាន QR Code!</div>
                <?php endif; ?>
                <!-- End Show QR Code -->

                <!-- Form for Upload QR Code -->
                <form action="qr-code-bank.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="qr_code">ជ្រើសរើស QR Code (PNG, JPG, JPEG):</label>
                        <input type="file" name="qr_code" id="qr_code" class="form-control-file"
                            accept=".jpg,.jpeg,.png" required>
                    </div>
                    <button type="submit" class="btn btn-success">បង្ហោះ QR Code</button>
                </form>
                <!-- End Form for Upload QR Code -->

            </div>
        </div>
    </div>
</body>

</html>





<div class="card-body">
    <p class="card-text">ឈ្មោះធនាគារ: </p>

    <img src="<?= './uploads/images_qr/' . htmlspecialchars($qr_bank['image_url']) ?>" alt="QR Code" class="img-fluid"
        style="width: 50%; object-fit: contain;">
</div>