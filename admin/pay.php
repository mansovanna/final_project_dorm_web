<?php
session_start();

// Include database connection
include '../conn_db.php';

if (!isset($_SESSION["admin_username"])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// Check payment  ID for update data 
if (isset($_GET['payment_id'])) {
    $payment_id = mysqli_real_escape_string($conn, $_GET['payment_id']);
} else {
    $payment_id = null; // Set to null if not provided
}
// Check if the student ID is provided in the URL
if (isset($_GET['student_id']) && isset($_GET['year'])) {
    // Sanitize the student ID to prevent SQL injection
    $student_id = mysqli_real_escape_string($conn, $_GET['student_id']);
    $year = mysqli_real_escape_string($conn, $_GET['year']);
    
    // Query to get user's profile information
    $query = "SELECT * FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "No student ID provided.";
    exit();
}


// select payment_summary
$query = "SELECT * FROM payment_summary LIMIT 1";
$result = mysqli_query($conn, $query);
if ($result) {
    $payment_summary = mysqli_fetch_assoc($result);
} else {
    echo "Error fetching payment summary: " . mysqli_error($conn);
    exit();
}

mysqli_close($conn);

include('../include/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
    <style>
         .color {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        a:link{
            text-decoration: none;
        }
     </style>
</head>

<body>
<div class="content-wrapper">
        <div class="">
            <form action="process_pay.php" method="post">
                <h4>បង់ថ្លៃស្នាក់នៅ</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_id">លេខសម្គាល់និស្សិត:</label>
                            <input type="text" name="student_id" id="student_id" class="form-control" value="<?php echo $user['student_id']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>ឈ្មោះនិស្សិត:</label>
                            <input type="text" name="user_name" value="<?php echo $user['lastname']; ?> <?php echo $user['name']; ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>អគារស្នាក់នៅ:</label>
                            <input type="text" name="building" class="form-control" value="<?php echo $user['building']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>បន្ទប់លេខ:</label>
                            <input type="text" name="room_number" class="form-control" value="<?php echo $user['room']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ថ្លៃស្នាក់នៅ:</label>
                            <input type="number" name="accommodation_fee" class="form-control" required oninput="calculateTotal()" value="<?php echo htmlspecialchars($payment_summary['room']); ?>">
                        </div>
                        <div class="form-group">
                            <label>ថ្លៃទឹក:</label>
                            <input type="number" name="water_fee" class="form-control" required oninput="calculateTotal()" value="<?php echo htmlspecialchars($payment_summary['water_fee']?? 0); ?>">
                        </div>
                        <div class="form-group">
                            <label>ថ្លៃភ្លើង:</label>
                            <input type="number" name="electricity_fee" class="form-control" required oninput="calculateTotal()" value="<?php echo htmlspecialchars($payment_summary['electricity_fee']?? 0); ?>">
                        </div>
                        <div class="form-group">
                            <label>បញ្ចុះតម្លៃ (%):</label>
                            <input type="number" name="discount" class="form-control" oninput="calculateTotal()">
                        </div>
                    </div>
                </div>
                <div class=" total-display">
                    <label>តម្លៃសរុប:</label>
                    <span id="total-amount" class="font-weight-bold">0.00</span> ៛
                </div>
                <div class="total-display" style="padding: 0;">
                    <label>ការបង់ប្រាក់សម្រាប់ឆ្នាំ:</label>
                    <span id="total-amount" class="font-weight-bold"><?php echo htmlspecialchars($year); ?></span>
                    <input type="numeric" name="year" value="<?php echo htmlspecialchars($year); ?>" hidden>
                    <input type="number" name="payment_id" value="<?php echo htmlspecialchars($payment_id??0); ?>" hidden>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" <?php echo $year < 2023 ? 'disabled' : ''; ?>>បង់ថ្លៃ</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function calculateTotal() {
            const accommodationFee = parseFloat(document.querySelector('input[name="accommodation_fee"]').value) || 0;
            const waterFee = parseFloat(document.querySelector('input[name="water_fee"]').value) || 0;
            const electricityFee = parseFloat(document.querySelector('input[name="electricity_fee"]').value) || 0;
            const discountPercent = parseFloat(document.querySelector('input[name="discount"]').value) || 0;

            const subtotal = accommodationFee + waterFee + electricityFee;
            const discountAmount = (subtotal * discountPercent) / 100;
            const total = subtotal - discountAmount;

            document.getElementById('total-amount').textContent = total.toFixed(2);
        }
    </script>
</body>
</html>