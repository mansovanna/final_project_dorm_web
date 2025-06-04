
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
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@900&family=Koulen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .shadow-lg {
            box-shadow: 0 0rem 0 !important;
        }
        /* .edit .btn-primary{
            position: relative;
            right: 37%;
            top: -66%;
        } */
        .col-lg-8{
			margin-bottom: 50px;
		}
    </style>
</head>
<body>
<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
    <div class="col-md-4 text-center">
        <?php if (!empty($user['img'])): ?>
            <img src="<?php echo $user['img']; ?>" class="img-fluid rounded" style="width: 160px; height: 200px; object-fit: cover;">
        <?php else: ?>
            <img src="img/user1.png" class="img-fluid rounded" style="width: 180px; height: 180px; object-fit: cover;">
        <?php endif; ?>
        <div class="col-12 mt-3 d-flex justify-content-end edit" style=" margin-bottom: 15px; margin-left: -55px;">
            <a class="btn btn-primary me-2 btn-sm" href="edit_pf.php">កែប្រែប្រវត្តិរូប</a>
            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#changePasswordModal">
                ប្តូរពាក្យសម្ងាត់
            </button>
        </div>
    </div>
    <div class="col-md-8">
        <div class="h3">ព័ត៌មានផ្ទាល់ខ្លួន</div>
        <table class="table table-striped">
            <tr><th colspan="2">ព័ត៌មានលម្អិត:</th></tr>
            <tr><th><i class=""></i> លេខសម្គាល់និស្សិត</th><td><?php echo $user['student_id']; ?></td></tr>
            <tr><th><i class=""></i> នាមត្រកូល នាមខ្លួន</th><td><?php echo $user['lastname']; ?> <?php echo $user['name']; ?></td></tr>
            <tr><th><i class=""></i> អក្សរឡាតាំង</th><td><?php echo $user['username']; ?></td></tr>
            <tr><th><i class=""></i> ភេទ</th><td><?php echo $user['gender']; ?></td></tr>
            <tr><th><i class=""></i> ថ្ងៃខែឆ្នាំកំណើត</th><td><?php echo $user['dob']; ?></td></tr>
            <tr><th><i class=""></i> អាសយដ្ឋាន</th><td><?php echo $user['address']; ?></td></tr>
            <tr><th><i class=""></i> លេខទូរស័ព្ទនិស្សិត</th><td><?php echo $user['phone_student']; ?></td></tr>
            <tr><th><i class=""></i> លេខទូរស័ព្ទអាណាព្យាបាល</th><td><?php echo $user['phone_parent']; ?></td></tr>
            <tr><th><i class=""></i> ជំនាញ</th><td><?php echo $user['skill']; ?></td></tr>
            <tr><th><i class=""></i> កម្រិតសិក្សា</th><td><?php echo $user['education_level']; ?></td></tr>
            <tr><th><i class=""></i> ឆ្នាំសិក្សា</th><td><?php echo $user['year']; ?></td></tr>
            <tr><th><i class=""></i> ថ្ងៃខែឆ្នាំចូលស្នាក់នៅ</th><td><?php echo $user['stay']; ?></td></tr>
            <tr><th><i class=""></i> អគារស្នាក់នៅ</th><td><?php echo $user['building']; ?></td></tr>
            <tr><th><i class=""></i> បន្ទប់លេខ</th><td><?php echo $user['room']; ?></td></tr>
        </table>
    </div>
</div>

 <!-- Change Password Modal -->
 <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">ប្តូរពាក្យសម្ងាត់</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="change_password_student.php" method="post">
                    <div class="form-group">
                        <label for="current_password">ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" id="toggleCurrentPassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_password">ពាក្យសម្ងាត់ថ្មី</label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" id="toggleNewPassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                        <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-primary">ប្តូរពាក្យសម្ងាត់</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
   
<script>
    // Toggle current password visibility
    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('current_password');
        const icon = this;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Toggle new password visibility
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('new_password');
        const icon = this;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Toggle confirm new password visibility
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('confirm_password');
        const icon = this;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>
<?php include('include/footer.php'); ?>