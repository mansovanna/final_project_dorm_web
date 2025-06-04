<?php
session_start();
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

$qr_codes = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qr_code"]) && isset($_POST['name'])) {
    $targetDir = "../uploads/images_qr/";
    $name_abank = $conn->real_escape_string($_POST['name']);

    $fileName = basename($_FILES["qr_code"]["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["qr_code"]["tmp_name"], $targetFile)) {
            // Insert into DB
            $sql = "INSERT INTO qr_code_bank (name, image_url) VALUES ('$name_abank', '$fileName')";
            if ($conn->query($sql) === TRUE) {
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, PNG, JPEG, and GIF files are allowed.";
    }
}

// Get latest QR
$get_data = "SELECT * FROM qr_code_bank";
$result_qr = $conn->query($get_data);
if ($result_qr && $result_qr->num_rows > 0) {
    $qr_codes = [];
    while ($row = $result_qr->fetch_assoc()) {
        $qr_codes[] = $row;
    }
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
        <div class="w-full d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-weight-bold mb-0">KH QR Code សម្រាប់បង់លុយ</h3>
            <!-- Button -->
            <button class="btn btn-primary text-white" data-toggle="modal" data-target="#addQrModal">
                បន្ថែម QR CODE
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addQrModal" tabindex="-1" role="dialog" aria-labelledby="addQrModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="" method="POST" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQrModalLabel">បន្ថែម QR CODE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">ឈ្មោះធនាគារ (Bank Name)</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="qr_code">រូបភាព QR Code</label>

                            <!-- Styled Input -->
                            <div class="custom-file">
                                <input type="file" name="qr_code" class="custom-file-input" id="qr_code" required>
                                <label class="custom-file-label" for="qr_code">ជ្រើសរើសរូបភាព...</label>
                            </div>

                            <!-- Preview Box -->
                            <div id="preview-container" class="mb-3" style="max-width: 200px; margin-top: 10px;">
                                <img id="preview-image" src="#" alt="Image Preview" class="img-thumbnail d-none" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">បិទ</button>
                        <button type="submit" class="btn btn-primary">បញ្ចូល</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-body d-flex align-items-start flex-wrap gap-2" style="gap: 1rem;">
                <?php if (!empty($qr_codes)): ?>
                    <?php foreach ($qr_codes as $qr): ?>
                        <div class="card" style="width: 200px;">
                            <img src="../uploads/images_qr/<?= htmlspecialchars($qr['image_url']) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($qr['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($qr['name']) ?></h5>
                                <p style="font-size: 12px;">Created at: <?= htmlspecialchars($qr['created_at']) ?></p>

                                <!-- Delete QR Code Button -->
                                <form action="delete_qr.php" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this QR code?');">
                                    <input type="hidden" name="qr_id" value="<?= htmlspecialchars($qr['id']) ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>មិនទាន់មាន QR CODE ទេ។</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script>
        document.getElementById("qr_code").addEventListener("change", function (e) {
            const fileInput = e.target;
            const previewImage = document.getElementById("preview-image");
            const fileLabel = fileInput.nextElementSibling;
            const file = fileInput.files[0];

            if (file) {
                fileLabel.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                    previewImage.classList.remove("d-none");
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.src = "#";
                previewImage.classList.add("d-none");
                fileLabel.textContent = "ជ្រើសរើសរូបភាព...";
            }
        });
    </script>

</body>

</html>