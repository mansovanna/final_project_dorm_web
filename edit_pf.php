<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}
include 'conn_db.php';

$user_id = $_SESSION['user_id'];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'];
	$lastname = $_POST['lastname'];
	$student_id = $_POST['student_id'];
	$username = $_POST['username'];
	$gender = $_POST['gender'];
	$dob = $_POST['dob'];
	$address = $_POST['address'];
	$phone_student = $_POST['phone_student'];
	$phone_parent = $_POST['phone_parent'];
	$skill = $_POST['skill'];
	$education_level = $_POST['education_level'];
	$year = $_POST['year'];
	$img_path = $user['img'];

	if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
		$img_tmp = $_FILES['img']['tmp_name'];
		$img_name = basename($_FILES['img']['name']);
		$img_folder = 'uploads/';
		$img_path = $img_folder . $img_name;
		move_uploaded_file($img_tmp, $img_path);
	}

	$update_query = "UPDATE register SET name=?, lastname=?, student_id=?, gender=?, username=?, dob=?, address=?, phone_student=?, phone_parent=?, skill=?, education_level=?, year=?, img=? WHERE student_id=?";
	$update_stmt = $conn->prepare($update_query);
	$update_stmt->bind_param("ssissssssssssi", $name, $lastname, $student_id, $gender, $username, $dob, $address, $phone_student, $phone_parent, $skill, $education_level, $year, $img_path, $user_id);
	if ($update_stmt->execute()) {
		header("Location: show_pf.php");
		exit();
	} else {
		echo "Update error: " . $conn->error;
	}
}
?>


<!DOCTYPE html>
<html lang="km">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>កែប្រែប្រវត្តិរូប</title>
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<style>
		.profile-img {
			width: 150px;
			height: 180px;
			object-fit: cover;
		}
	</style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
	<?php include('include/header_student.php'); ?>

	<form method="POST" enctype="multipart/form-data" class="container mt-5 flex-grow-1">
		<div class="card shadow-sm p-4">
			<div class="row">
				<!-- Profile Image -->
				<div class="col-md-4 d-flex flex-column align-items-center mb-3">
					<div class="position-relative">
						<img src="<?= htmlspecialchars($user['img'] ?: 'img/user1.png') ?>"
							class="rounded-circle border border-secondary shadow-sm"
							style="width: 160px; height: 160px; object-fit: cover;">
						<label for="formFile"
							class="position-absolute bottom-0 end-0 bg-primary text-white px-2 py-1 rounded small"
							style="cursor: pointer;">
							<i class="bi bi-camera-fill"></i>
						</label>
						<input type="file" class="d-none" id="formFile" name="img">
					</div>
					<small class="text-muted mt-2">ជ្រើសរូបភាពថ្មី</small>
				</div>


				<!-- Profile Fields -->
				<div class="col-md-8">
					<h5 class="mb-3 text-primary">ព័ត៌មានផ្ទាល់ខ្លួន</h5>
					<div class="row g-2">
						<?php
						$fields = [
							"student_id" => "លេខសម្គាល់និស្សិត",
							"lastname" => "នាមត្រកូល",
							"name" => "នាមខ្លួន",
							"username" => "អក្សរឡាតាំង",
							"gender" => "ភេទ",
							"dob" => "ថ្ងៃខែឆ្នាំកំណើត",
							"address" => "អាសយដ្ឋាន",
							"phone_student" => "លេខទូរស័ព្ទនិស្សិត",
							"phone_parent" => "លេខទូរស័ព្ទអាណាព្យាបាល",
							"skill" => "ជំនាញ",
							"education_level" => "កម្រិតសិក្សា",
							"year" => "ឆ្នាំសិក្សា"
						];
						foreach ($fields as $field => $label): ?>
							<div class="col-md-6">
								<label class="form-label"><?= $label ?></label>
								<input type="text" class="form-control" name="<?= $field ?>"
									value="<?= htmlspecialchars($user[$field]) ?>">
							</div>
						<?php endforeach; ?>
					</div>
					<div class="mt-4 d-flex justify-content-between">
						<a href="show_pf.php" class="btn btn-secondary">ត្រឡប់</a>
						<button type="submit" class="btn btn-primary">រក្សាទុក</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<footer class="bg-white text-muted text-center py-3 mt-auto">
		<?php include('include/footer.php'); ?>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>