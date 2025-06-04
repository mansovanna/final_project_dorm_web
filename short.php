
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
    echo "User not found.";
    exit();
}

include('include/header_student.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
    <style>
        .shadow-lg {
            box-shadow: 0 0rem 0 !important;
        }
		.col-lg-8{
			margin-bottom: 90px;
			
		}
    </style>
</head>
<body>
		<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
			<div class="col-md-4 text-center">
				<img src="<?php echo $user['img'] ?: 'img/user1.png'; ?>" class="img-fluid rounded" style="width: 160px; height: 200px; object-fit: cover; border: 1px solid #333;">
			</div>


			<div class="col-md-8 mt-3">
				<div class="h2">ព័ត៌មានផ្ទាល់ខ្លួន</div>
				<table class="table table-striped">
					<tr><th colspan="2">ព័ត៌មានលម្អិត:</th></tr>
					<tr><th><i class=""></i> លេខសម្គាល់និស្សិត</th><td><?php echo $user['student_id']; ?></td></tr>
					<tr><th><i class=""></i> នាមត្រកូល</th><td><?php echo $user['lastname']; ?></td></tr>
					<tr><th><i class=""></i> នាមខ្លួន</th><td><?php echo $user['name']; ?></td></tr>
					<tr><th><i class=""></i> ភេទ</th><td><?php echo $user['gender']; ?></td></tr>
                    <tr><th><i class=""></i> លេខទូរស័ព្ទនិស្សិត</th><td><?php echo $user['phone_student']; ?></td></tr>
				</table>
			</div>
		</div>

		
</body>
</html>
<?php include('include/footer.php'); ?>