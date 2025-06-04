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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone_student = mysqli_real_escape_string($conn, $_POST['phone_student']);
    $phone_parent = mysqli_real_escape_string($conn, $_POST['phone_parent']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $education_level = mysqli_real_escape_string($conn, $_POST['education_level']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
	$img_path = $user['img']; // Default to current image path

    // Handle image upload if a new image is provided
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_tmp = $_FILES['img']['tmp_name'];
        $img_name = basename($_FILES['img']['name']);
        $img_folder = 'uploads/';
        $img_path = $img_folder . $img_name;

        if (!move_uploaded_file($img_tmp, $img_path)) {
            die("Failed to upload image.");
        }
    } elseif ($_FILES['img']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "Error uploading image: " . $_FILES['img']['error'];
        exit();
    }

    // Update user's profile information in the database
    $update_query = "UPDATE register SET name=?, lastname=?, student_id=?, gender=?, username=?, dob=?, address=?, phone_student=?, phone_parent=?, skill=?, education_level=?, year=?, img=? WHERE student_id=?";
    $update_stmt = $conn->prepare($update_query);
    
    // Bind parameters to the statement
    $update_stmt->bind_param("ssissssssssssi", $name, $lastname, $student_id, $gender, $username, $dob, $address, $phone_student, $phone_parent, $skill, $education_level, $year, $img_path, $user_id);

    if ($update_stmt->execute()) {
        // Update session variables with new data
        $_SESSION['student_id'] = $student_id;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['name'] = $name;
        $_SESSION['username'] = $username;
        $_SESSION['gender'] = $gender;
        $_SESSION['dob'] = $dob;
        $_SESSION['address'] = $address;
        $_SESSION['phone_student'] = $phone_student;
        $_SESSION['phone_parent'] = $phone_parent;
        $_SESSION['skill'] = $skill;
        $_SESSION['education_level'] = $education_level;
        $_SESSION['year'] = $year;
        $_SESSION['img'] = $img_path;

        // Redirect to profile page after successful update
        header("Location: show_pf.php");
        exit();
    } else {
        echo "Error updating profile information: " . $conn->error;
    }
}
// Close database connection
$stmt->close();
$conn->close();
?>


<?php
include('include/header_student.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Profile</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>
<form method="post" enctype="multipart/form-data">
	<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
		<div class="col-md-4 text-center">
			<img src="<?php echo htmlspecialchars($user['img']) ?: 'img/user1.png'; ?>" class="img-fluid rounded" style="width: 160px; height: 200px; object-fit: cover;">
				<div class="mb-3 mt-3">
					<label for="formFile" class="form-label">ជ្រើសរើសរូបភាពរបស់អ្នក</label>
					<input class="form-control" type="file" id="formFile" name="img">
				</div>
				<div><small class="js-error js-error-image text-danger"></small></div>
		</div>
		<div class="col-md-8">
			<div class="h2">កែប្រែប្រវត្តិរូប</div>
				<table class="table table-striped">
					<tr><th colspan="2">ព័ត៌មានលម្អិត:</th></tr>
					<tr><th><i class=""></i>លេខសម្គាល់និស្សិត</th>
						<td>
							<input type="text" class="form-control"id="student_id" name="student_id" value="<?php echo $user['student_id']; ?>" >
							<div><small class="js-error js-error-email text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class="bi bi-person-circle"></i> នាមត្រកូល</th>
						<td>
							<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class="bi bi-person-square"></i> នាមខ្លួន</th>
						<td>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> អក្សរឡាតាំង​</th>
						<td>
							<input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> ភេទ</th>
						<td>
							<input type="text" class="form-control" id="gender" name="gender" value="<?php echo $user['gender']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> ថ្ងៃខែឆ្នាំកំណើត​</th>
						<td>
							<input type="text" class="form-control" id="dob" name="dob" value="<?php echo $user['dob']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> អាសយដ្ឋាន​</th>
						<td>
							<input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> លេខទូរស័ព្ទនិស្សិត</th>
						<td>
							<input type="text" class="form-control" id="phone_student" name="phone_student" value="<?php echo $user['phone_student']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> លេខទូរស័ព្ទអាណាព្យាបាល</th>
						<td>
							<input type="text" class="form-control" id="phone_parent" name="phone_parent" value="<?php echo $user['phone_parent']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> ជំនាញ</th>
						<td>
							<input type="text" class="form-control" id="skill" name="skill" value="<?php echo $user['skill']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> កម្រិតសិក្សា</th>
						<td>
							<input type="text" class="form-control" id="education_level" name="education_level" value="<?php echo $user['education_level']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
					<tr><th><i class=""></i> ឆ្នាំ</th>
						<td>
							<input type="text" class="form-control" id="year" name="year" value="<?php echo $user['year']; ?>">
							<div><small class="js-error js-error-lastname text-danger"></small></div>
						</td>
					</tr>
				</table>
				<div class="p-2">
					<button class="btn btn-primary float-end">រក្សាទុក</button>
					<a href="show_pf.php">
						<label class="btn btn-secondary">ត្រឡប់</label>
					</a>
				</div>
			</div>
		</div>
	</div>
</form>
</body>
</html>
<?php include('include/footer.php'); ?> 
