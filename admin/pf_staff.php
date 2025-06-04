<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include '../conn_db.php';

// Retrieve user data from session
$username = $_SESSION['admin_username'];

// Query to get user's profile information
$query = "SELECT * FROM staff WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

if(isset($_POST['submit'])) {
    // Process form submission
    $id = $_POST['id'];
    $staff_Name = $_POST['staff_Name'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $Email = $_POST['Email'];
    
    // Check if a new image file is uploaded
    if(isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_name = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        $img_path = "uploads/" . $img_name; // Change this path as per your requirement

        // Move the uploaded image to the desired location
        move_uploaded_file($img_tmp_name, $img_path);

        // Update the database with the new image path
        $sql = "UPDATE staff SET staff_Name=?, username=?, phone_number=?, Email=?, img=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $staff_Name, $username, $phone_number, $Email, $img_path, $id);
    } else {
        // Update the database without changing the image path
        $sql = "UPDATE staff SET staff_Name=?, username=?, phone_number=?, Email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $staff_Name, $username, $phone_number, $Email, $id);
    }
    if ($stmt->execute()) {
        echo '<script>window.location.href = "pf_staff.php";</script>';
    } else {
        echo "Error updating profile.";
    }

}
?>

<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../style/style_edit.css">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@900&family=Koulen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
    <div class="content-wrapper">
        <h3 class="mb-4 text-center font-weight-bold">ព័ត៌មានផ្ទាល់ខ្លួន</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 offset-md-2"> 
                    <button type="submit" name="submit" class="btn btn-primary">រក្សាទុក</button>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">
                        ប្តូរពាក្យសម្ងាត់
                    </button>
                </div>  
            </div>
            <div class="row">
                <div class="col-md-8 offset-md-2 mt-3">  
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>រូបភាពបច្ចុប្បន្ន</label>
                                        <div class="d-flex justify-content-center">
                                            <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Current Staff Picture" class="img-fluid " style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="img">ជ្រើសរើសរូបភាពថ្មី</label>
                                        <input type="file" name="img" id="img" class="form-control-file">
                                    </div>
                                
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                    
                                    <div class="form-group">
                                        <label for="staff_Name">ឈ្មោះ</label>
                                        <input type="text" name="staff_Name" id="staff_Name" class="form-control" value="<?php echo htmlspecialchars($user['staff_Name']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="username">អក្សរឡាតាំង</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phone_number">លេខទូរស័ព្ទ</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="text" name="Email" id="Email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>">
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                <form id="changePasswordForm" action="change_password.php" method="post">
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
                        <label for="confirm_new_password">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                        <div class="input-group">
                            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" required>
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
        const passwordField = document.getElementById('confirm_new_password');
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
