<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'conn_db.php';

// 
$isMessage = null;
$get_year = $_GET['year'] ?? null;
// Get student info
$student_id = $_SESSION['user_id'];
$sql = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();


// select field from payment_summary table -------------------------------
$select_qr_code_bank = "SELECT * FROM qr_code_bank ";
$response = $conn->prepare($select_qr_code_bank);
$response->execute();
$qr_code_bank_res = $response->get_result();
$qr_code_bank = $qr_code_bank_res->fetch_all();
// End Select field from payment_summary table ----------------------------

// End Select field from payment_summary table

// Select field from qr_code_bank table -----------------------------------
$select_qr_code_bank = "SELECT * FROM qr_code_bank ";
$response = $conn->prepare($select_qr_code_bank);
$response->execute();
$qr_code_bank_res = $response->get_result();
$qr_code_bank = $qr_code_bank_res->fetch_all();


// End Select field from qr_code_bank table -------------------------------

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get student ID and year from POST data
    $student_id = $_POST['student_id'] ?? null;
    $get_year = $_POST['year'] ?? null;
    if (!$student_id || !$get_year) {
        echo "<script>alert('ព័ត៌មានមិនគ្រប់គ្រាន់។');</script>";
        exit;
    }


    // Handle file upload
    if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['qr_code']['tmp_name'];
        $fileName = $_FILES['qr_code']['name'];
        $fileSize = $_FILES['qr_code']['size'];
        $fileType = $_FILES['qr_code']['type'];

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('សូមជ្រើសរើសឯកសារដែលមានទ្រង់ទ្រាយ JPG ឬ PNG ប៉ុណ្ណោះ។');</script>";
            exit;
        }

        // Move uploaded file to the desired directory
        $uploadFileDir = 'uploads/images_qr/';
        $dest_path = "{$uploadFileDir}{$fileName}";

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // Insert data into payment_summary table
            // Get student info for payment fields
            $user_name = $student['lastname'] . ' ' . $student['name'];
            $building = $student['building'];
            $room_number = $student['room'];

            // Get total payment info
            $total_payment = getTotalPrice($conn);
            $accommodation_fee = $total_payment['room'];
            $discount = $total_payment['discount'];
            $water_fee = $total_payment['water_fee'];
            $electricity_fee = $total_payment['electricity_fee'];
            $discountAmount = ($accommodation_fee * $discount) / 100;
            $total_fee = ($accommodation_fee - $discountAmount) + $water_fee + $electricity_fee;
            // Make sure the status value matches one of the ENUM values in your database, e.g., 'Pending', 'Approved', 'Rejected'
            $status = 'Pending'; // Change this if your ENUM values are different
            $image = $fileName;
            $date = $get_year;

            $insert_query = "INSERT INTO payment (
                student_id, 
                building, 
                room_number, 
                accommodation_fee, 
                discount, 
                water_fee, 
                electricity_fee, 
                total_fee, 
                status, 
                image, 
                date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param(
                "issddddsssi",
                $student_id,
                $building,
                $room_number,
                $accommodation_fee,
                $discount,
                $water_fee,
                $electricity_fee,
                $total_fee,
                $status,
                $image,
                $date
            );
            if ($stmt->execute()) {
                echo "<script>alert('បង្ហោះ QR Code សម្រេច!');</script>";
                // Redirect to payment history page
                header("Location: his_pay.php");
                exit();
            } else {
                echo "<script>alert('មានបញ្ហាក្នុងការបង្ហោះ QR Code។');</script>";
            }
           
        } else {
            echo "<script>alert('មិនអាចផ្ទុកឯកសារឡើងបានទេ។');</script>";
        }
    }
    // Get student data
    $select_data = "SELECT * FROM register WHERE student_id  = ?";
    $response = $conn->prepare($select_data);
    $response->bind_param('i', $student_id);
    $response->execute();
    $res = $response->get_result();

    // echo "<script> alert('This is: $res');</script>";
}

// select payment_summary table to get total price
$total_payment = getTotalPrice($conn);
function getTotalPrice($conn){
    $select_total_price = "SELECT * FROM payment_summary LIMIT 1";
    $response = $conn->prepare($select_total_price);
    $response->execute();
    $total_price = $response->get_result()->fetch_assoc();  
    return $total_price;
}

$conn->close();
include 'include/header_student.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pay Now</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="font-weight-bold">បង់ថ្លៃស្នាក់នៅ</h4>
            <a href="his_pay.php" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
        </div>

        <!-- QR Code and Upload Section -->
        <div class="row">
            <!-- QR Code Display Block -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if (!empty($qr_code_bank)): ?>
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                            <p class="card-text">សូមស្កេន QR Code ខាងក្រោម ដើម្បីបង់ប្រាក់</p>

                            <?php foreach ($qr_code_bank as $index => $qr_bank): ?>
                                <div class="qr-wrapper <?= $index === 0 ? '' : 'd-none' ?>" data-index="<?= $index ?>"
                                    data-bank="<?= htmlspecialchars($qr_bank[1]) ?>">
                                    <img src="./uploads/images_qr/<?= htmlspecialchars($qr_bank[2]) ?>"
                                        alt="QR code for <?= htmlspecialchars($qr_bank[1]) ?>" class="img-fluid mb-3"
                                        style="max-width: 300px;">
                                    <br>
                                    <a href="./uploads/images_qr/<?= htmlspecialchars($qr_bank[2]) ?>"
                                       download="<?= htmlspecialchars($qr_bank[2]) ?>"
                                       class="btn btn-outline-info btn-sm mt-2">
                                        ទាញយករូបភាព
                                    </a>
                                </div>
                            <?php endforeach; ?>

                            <button id="switchBtn" class="btn btn-primary mt-3">
                                ប្ដូរទៅជា៖ <span id="bankName"><?= htmlspecialchars($qr_code_bank[0][1]) ?></span>
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="card-body text-center">
                            <h5 class="card-title">QR Code</h5>
                            <p class="card-text">មិនមាន QR Code សម្រាប់បង់ប្រាក់</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upload Form Block -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="mb-3">
                                    <label class="font-weight-bold">ព័ត៌មានសរុបតម្លៃស្នាក់នៅ:</label>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ថ្លៃស្នាក់នៅ
                                            <span><?= number_format($total_payment['room'], 2) ?> ៛</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ថ្លៃទឹក
                                            <span><?= number_format($total_payment['water_fee'], 2) ?> ៛</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            ថ្លៃភ្លើង
                                            <span><?= number_format($total_payment['electricity_fee'], 2) ?> ៛</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            បញ្ចុះតម្លៃ
                                            <span><?= number_format($total_payment['discount'], 2) ?> %</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                            សរុបតម្លៃ
                                            <span>
                                                <?php
                                                    // Calculate total: (room - discount%) + water + electricity
                                                    $room = (float)$total_payment['room'];
                                                    $water = (float)$total_payment['water_fee'];
                                                    $electricity = (float)$total_payment['electricity_fee'];
                                                    $discount = (float)$total_payment['discount'];
                                                    $discountAmount = ($room * $discount) / 100;
                                                    $total = ($room - $discountAmount) + $water + $electricity;
                                                    echo number_format($total, 2) . " ៛";
                                                ?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <label for="qr_code" class="font-weight-bold">ជ្រើសរើស QR Code (PNG, JPG, JPEG):</label>
                                <div class="custom-file">
                                    <input type="file" name="qr_code" id="qr_code" class="custom-file-input"
                                        accept=".jpg,.jpeg,.png" required>
                                    <label class="custom-file-label" for="qr_code">ជ្រើសរើសឯកសារ...</label>
                                </div>
                                <input type="text" name="year" value="<?= htmlspecialchars($get_year) ?>" hidden>
                                <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">
                                <small class="form-text text-muted mt-2">
                                    សូមជ្រើសរើសរូបភាពដែលមានទ្រង់ទ្រាយ PNG, JPG, ឬ JPEG ប៉ុណ្ណោះ។
                                </small>
                            </div>
                            <script>
                                // Show selected file name
                                document.querySelector('.custom-file-input').addEventListener('change', function (e) {
                                    var fileName = document.getElementById("qr_code").files[0]?.name || "ជ្រើសរើសឯកសារ...";
                                    var nextSibling = e.target.nextElementSibling
                                    nextSibling.innerText = fileName
                                });
                            </script>
                            <button type="submit" class="btn btn-success">បង្ហោះ QR Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Script for Calculating Total -->
    <script>
        const accommodationFeeInput = document.querySelector('input[name="accommodation_fee"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const waterFeeInput = document.querySelector('input[name="water_fee"]');
        const electricityFeeInput = document.querySelector('input[name="electricity_fee"]');
        const totalPriceInput = document.getElementById('total_price');

        function calculateTotal() {
            const accommodation = parseFloat(accommodationFeeInput?.value) || 0;
            const discount = parseFloat(discountInput?.value) || 0;
            const water = parseFloat(waterFeeInput?.value) || 0;
            const electricity = parseFloat(electricityFeeInput?.value) || 0;

            const discountAmount = (accommodation * discount) / 100;
            const total = (accommodation - discountAmount) + water + electricity;

            if (totalPriceInput) {
                totalPriceInput.value = total.toFixed(2) + "៛";
            }
        }

        accommodationFeeInput?.addEventListener('input', calculateTotal);
        discountInput?.addEventListener('input', calculateTotal);
        waterFeeInput?.addEventListener('input', calculateTotal);
        electricityFeeInput?.addEventListener('input', calculateTotal);
    </script>


    <script>
        const qrWrappers = document.querySelectorAll('.qr-wrapper');
        const switchBtn = document.getElementById('switchBtn');
        const bankNameSpan = document.getElementById('bankName');
        let currentIndex = 0;

        if (switchBtn && qrWrappers.length > 1) {
            switchBtn.addEventListener('click', () => {
                // Hide current QR
                qrWrappers[currentIndex].classList.add('d-none');

                // Update index
                currentIndex = (currentIndex + 1) % qrWrappers.length;

                // Show next QR
                qrWrappers[currentIndex].classList.remove('d-none');

                // Update bank name on button
                const nextBankName = qrWrappers[currentIndex].dataset.bank;
                bankNameSpan.textContent = nextBankName;
            });
        }
    </script>


    <?php include 'include/footer.php'; ?>
</body>

</html>



<!-- 
<div class="col-md-6">
    <form action="payment_form.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>">

        <div class="form-group">
            <label>ឈ្មោះ:</label>
            <input type="text" class="form-control"
                value="<?= htmlspecialchars($student['lastname'] . ' ' . $student['name']) ?>" disabled>
        </div>

        <div class="form-group">
            <label>អគារ:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($student['building']) ?>" disabled>
        </div>

        <div class="form-group">
            <label>បន្ទប់:</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($student['room']) ?>" disabled>
        </div>

        <div class="form-group">
            <label>ថ្លៃស្នាក់នៅ:</label>
            <input type="number" name="accommodation_fee" class="form-control" required>
        </div>

        <div class="form-group">
            <label>បញ្ចុះតម្លៃ (%):</label>
            <input type="number" name="discount" class="form-control" value="0">
        </div>

        <div class="form-group">
            <label>ថ្លៃទឹក:</label>
            <input type="number" name="water_fee" class="form-control" required>
        </div>

        <div class="form-group">
            <label>ថ្លៃភ្លើង:</label>
            <input type="number" name="electricity_fee" class="form-control" required>
        </div>

        <div class="form-group">
            <label>សរុបតម្លៃ (Total Price):</label>
            <input type="text" name="total_price" id="total_price" class="form-control" readonly>
        </div>
        <button type="submit" class="btn btn-success btn-block mb-4">បញ្ជូន</button>
    </form>
</div> -->