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

// select data from student request leave count data is of number
$query_count = "SELECT COUNT(*) as request_count FROM reques_alaw WHERE student_id = ?";
$stmt_count = $conn->prepare($query_count);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$request_count = 0;
if ($result_count->num_rows === 1) {
	$row = $result_count->fetch_assoc();
	$request_count = $row['request_count'];
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

		.col-lg-8 {
			margin-bottom: 90px;

		}
	</style>
</head>

<body>
	<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
		<!-- <div class="col-md-4 text-center">
				<img src="<?php echo $user['img'] ?: 'img/user1.png'; ?>" class="img-fluid rounded" style="width: 160px; height: 200px; object-fit: cover; border: 1px solid #333;">
			</div> -->


		<!-- <div class="col-md-8 mt-3">
				<div class="h2">ព័ត៌មានផ្ទាល់ខ្លួន</div>
				<table class="table table-striped">
					<tr><th colspan="2">ព័ត៌មានលម្អិត:</th></tr>
					<tr><th><i class=""></i> លេខសម្គាល់និស្សិត</th><td><?php echo $user['student_id']; ?></td></tr>
					<tr><th><i class=""></i> នាមត្រកូល</th><td><?php echo $user['lastname']; ?></td></tr>
					<tr><th><i class=""></i> នាមខ្លួន</th><td><?php echo $user['name']; ?></td></tr>
					<tr><th><i class=""></i> ភេទ</th><td><?php echo $user['gender']; ?></td></tr>
					<tr><th><i class=""></i> លេខទូរស័ព្ទនិស្សិត</th><td><?php echo $user['phone_student']; ?></td></tr>
				</table>
			</div> -->


		<!-- Block Content of Information of student -->
		<div class="card col-md-4 text-center border-0 shadow-lg border-end">
			<!--  -->

			<div class="h2">ព័ត៌មានផ្ទាល់ខ្លួន</div>
			<picture>
				<source srcset="<?php echo $user['img'] ?: 'img/user1.png'; ?>" type="image/svg+xml">
				<img src="<?php echo $user['img'] ?: 'img/user1.png'; ?>" class="img-fluid img-thumbnail" alt="...">
			</picture>
		</div>

		<!-- Block Content of Small box for requst law and payment -->

		<div class="col-md-8 mt-3 gap-3" style="gap: 1rem;">
			<!-- small box x 2 info and danger -->
			<!-- -------------- -->
			<div class="col-md-6 mb-3">
				<div class="bg-danger p-1 rounded text-white text-center row shadow-lg row-cols-1 row-cols-md-2 g-4">
					<div class="col w-screen">
						<h6 class="mt-2 whitespace-nowrap">ចំនួនច្បាប់កំពុងស្នើរសុំ</h5>

							<h1 class="font-weight-bold"><?php echo $request_count; ?></h1>

					</div>
					<i class="bi bi-journal-text" style="font-size: 5rem;"></i>


				</div>
			</div>

			<!-- Block request count student payment -->
			<div class="col-md-6 mb-3">
				<div class="bg-primary p-1 rounded text-white text-center row shadow-lg row-cols-1 row-cols-md-2 g-4">
					<div class="col w-screen">
						<h6 class="mt-2 whitespace-nowrap">ចំនួនមិនទាន់បង់ប្រាក់</h5>

							<h1 class="font-weight-bold"><?php echo 0; ?></h1>

					</div>
					<i class="bi bi-journal-text" style="font-size: 5rem;"></i>


				</div>
			</div>
		</div>
	</div>
	</div>


</body>

</html>
<?php include('include/footer.php'); ?>