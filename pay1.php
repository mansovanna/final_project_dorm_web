<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include 'conn_db.php';

// Retrieve user data from session
$user_id = $_SESSION['user_id'];

// Query to get user's profile information
$query = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found."; // Handle case where user doesn't exist (though this should ideally never happen)
    exit();
}
include('include/header_student.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
         .color {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .container{
            margin-bottom: 40px;
        }
        a:link{
            text-decoration: none;
        }
     </style>
</head>

<body>
<div class="container">
        <div class="color">
            <form action="process_pay.php" method="post">
                <h4>បង់ថ្លៃស្នាក់នៅ</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_id">លេខសម្គាល់និស្សិត:</label>
                            <input type="text" name="student_id" class="form-control" value="<?php echo $user['student_id']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="user_name">ឈ្មោះនិស្សិត:</label>
                            <input type="text" name="user_name" value="<?php echo $user['lastname']; ?> <?php echo $user['name']; ?>" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="dorm">អគារស្នាក់នៅ:</label>
                            <input type="text" name="building" class="form-control" value="<?php echo $user['building']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="room_number">បន្ទប់លេខ:</label>
                            <input type="text" name="room_number" class="form-control" value="<?php echo $user['room']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="accommodation_fee">ថ្លៃស្នាក់នៅ:</label>
                            <input type="number" name="accommodation_fee" class="form-control" required oninput="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="water_fee">ថ្លៃទឹក:</label>
                            <input type="number" name="water_fee" class="form-control" required oninput="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="electricity_fee">ថ្លៃភ្លើង:</label>
                            <input type="number" name="electricity_fee" class="form-control" required oninput="calculateTotal()">
                        </div>
                        <div class="form-group">
                            <label for="discount">បញ្ចុះតម្លៃ (%):</label>
                            <input type="number" name="discount" class="form-control" oninput="calculateTotal()">
                        </div>
                    </div>
                </div>
                <div class="form-group total-display">
                    <label for="total-amount">តម្លៃសរុប:</label>
                    <span id="total-amount" class="font-weight-bold">0.00</span> ៛
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">បង់ថ្លៃ</button>
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

<?php include('include/footer.php'); ?>