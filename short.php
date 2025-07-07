
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}

include 'conn_db.php';

$user_id = $_SESSION['user_id'];

// Get user profile
$query = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
	$user = $result->fetch_assoc();
} else {
	echo "User not found.";
	exit();
}

// Count leave requests
$query_count = "SELECT COUNT(*) as request_count FROM reques_alaw WHERE student_id = ?";
$stmt_count = $conn->prepare($query_count);
$stmt_count->bind_param("i", $user_id);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$request_count = ($result_count->num_rows === 1) ? $result_count->fetch_assoc()['request_count'] : 0;
?>
<!DOCTYPE html>
<html lang="km">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Student Profile</title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/bootstrap-icons.css">
	<style>
		body,
		html {
			height: 100%;
			margin: 0;
			padding: 0;
			overflow-x: hidden;
		}

		.wrapper {
			display: flex;
			flex-direction: column;
			min-height: 100vh;
		}

		.content {
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.nowrap {
			white-space: nowrap;
		}

		.profile-img {
			width: 150px;
			height: 150px;
			object-fit: cover;
		}
	</style>
</head>

<body>
	<div class="wrapper">
		<?php include('include/header_student.php'); ?>

		<!-- Main Content Centered -->
		<div class="container mt-5 ">
			<div class="row justify-content-center">
				<div class="border rounded p-3 shadow-lg bg-white">
					<div class="row p-3" style="gap: 0px;">

						<!-- Profile Card -->
						<div class="card col-md-4 text-center border-1  p-3">
							<div class="h5">ព័ត៌មានផ្ទាល់ខ្លួន</div>
							<picture>
								<source srcset="<?php echo $user['img'] ?: 'img/user1.png'; ?>" type="image/svg+xml">
								<img src="<?php echo $user['img'] ?: 'img/user1.png'; ?>"
									class="img-fluid img-thumbnail rounded-circle" alt="Profile"
									style="width: 150px; height: 150px; object-fit: cover;">
							</picture>

							<!-- show information -->

							<div class="mt-3" style="margin-top: 10px;">
				
								<div class="col-md-12 text-start">
									<p class="text-secondary mb-1">ឈ្មោះ:
										<?php echo htmlspecialchars($user['lastname'].' ' .$user['name']); ?>
									</p>
									<p class="text-secondary mb-1">លេខសម្គាល់:
										<?php echo htmlspecialchars($user['student_id']); ?>
									</p>
									<p class="text-secondary mb-1">ភេទ:
										<?php echo htmlspecialchars($user['gender']); ?>
									</p>
									<p class="text-secondary mb-1">លេខទូរស័ព្ទ:
										<?php echo htmlspecialchars($user['phone_student']); ?>
									</p>
								</div>

							</div>

						</div>

						<!-- Request & Payment Summary -->
						<div class="col-md-8 mt-3">
							<div class="row">
								<!-- Leave Request Count -->
								<div class="col-md-6 mb-3">
									<div
										class="bg-danger p-3 rounded text-white text-center shadow-lg d-flex align-items-center justify-content-between">
										<div>
											<h6 class="mt-2 nowrap">ចំនួនច្បាប់កំពុងស្នើរសុំ</h6>
											<h1 class="fw-bold"><?php echo $request_count; ?></h1>
										</div>
										<i class="bi bi-calendar-check" style="font-size: 4rem;"></i>
									</div>
								</div>

								<!-- Payment Pending Count -->
								<div class="col-md-6 mb-3">
									<div
										class="bg-primary p-3 rounded text-white text-center shadow-lg d-flex align-items-center justify-content-between">
										<div>
											<h6 class="mt-2 nowrap">ចំនួនមិនទាន់បង់ប្រាក់</h6>
											<h1 class="fw-bold">0</h1>
										</div>
										<i class="bi bi-cash-coin" style="font-size: 4rem;"></i>
									</div>
								</div>
							</div>
						</div>
					</div> <!-- /.row -->
				</div> <!-- /.col-lg-8 -->
			</div> <!-- /.row -->
		</div> <!-- /.container -->

		<!-- Sticky Footer -->
		<footer class="bg-light text-center text-muted py-3 mt-auto">
			<?php include('include/footer.php'); ?>
		</footer>
	</div>
</body>

</html>