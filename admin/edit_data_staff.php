<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Data</title>
    <link rel="stylesheet" href="../style/style_edit.css">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@900&family=Koulen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>
    <?php include("../include/header.php"); ?>
    <?php
    include "../conn_db.php"; // Include your database connection file
    
    if (isset($_POST['submit'])) {
        // Process form submission
        $id = $_POST['id'];
        $staff_Name = $_POST['staff_Name'];
        $username = $_POST['username'];
        $phone_number = $_POST['phone_number'];
        $Email = $_POST['Email'];
        
        // Check if a new image file is uploaded
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
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
            echo '<script>window.location.href = "data_staff.php";</script>';
            exit();
        } else {
            echo "Error updating staff data: " . $conn->error;
        }

        $stmt->close();
    } elseif (isset($_POST['update_password'])) {
        // Handle password update
        $id = $_POST['id'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt the new password
        
        // Update password in the database
        $sql = "UPDATE staff SET password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password, $id);

        if ($stmt->execute()) {
            echo '<script>alert("ពាក្យសម្ងាត់ត្រូវបានប្តូរដោយជោគជ័យ!"); window.location.href = "data_staff.php";</script>';
            exit();
        } else {
            echo "Error updating password: " . $conn->error;
        }
    } else {
        // Display the form for editing staff data
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM staff WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
    ?>
    <div class="content-wrapper">
        <h3 class="mb-4" style="text-align: center; font-weight: bold;">កែប្រែទិន្នន័យបុគ្គលិក</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8 offset-md-2 align-items-center">
                    <div class="button">
                        <a href="data_staff.php" class="btn btn-default btn-secondary"><i class="fas fa-share fa-flip-horizontal fa-fw"></i> ត្រឡប់</a>
                        <button type="submit" name="submit" class="btn btn-primary">រក្សាទុក</button>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updatePasswordModal">កែប្រែកូដសំងាត់</button>
                    </div>
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
                                        <div class="d-flex">
                                            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Current Staff Picture" class="img-fluid" style="width: 100%;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="img">ជ្រើសរើសរូបភាពថ្មី</label>
                                        <input type="file" name="img" id="img" class="form-control-file">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    
                                    <div class="form-group">
                                        <label for="staff_Name">ឈ្មោះ</label>
                                        <input type="text" name="staff_Name" id="staff_Name" class="form-control" value="<?php echo htmlspecialchars($row['staff_Name']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="username">អក្សរឡាតាំង</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($row['username']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phone_number">លេខទូរស័ព្ទ</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?php echo htmlspecialchars($row['phone_number']); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="Email">Email</label>
                                        <input type="text" name="Email" id="Email" class="form-control" value="<?php echo htmlspecialchars($row['Email']); ?>">
                                    </div>
                                </div>
                            </div>       
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal for Updating Password -->
    <div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordModalLabel">កែប្រែកូដសំងាត់</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="passwordUpdateForm">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <div class="form-group">
                            <label for="password">កូដសំងាត់ថ្មី</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePasswordVisibility('password')">
                                        <i id="eye-icon-password" class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">បញ្ជាក់កូដសំងាត់</label>
                            <div class="input-group">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                <div class="input-group-append">
                                    <span class="input-group-text" onclick="togglePasswordVisibility('confirm_password')">
                                        <i id="eye-icon-confirm_password" class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">បោះបង់</button>
                            <button type="submit" name="update_password" class="btn btn-primary">រក្សាទុក</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility(fieldId) {
            var field = document.getElementById(fieldId);
            var icon = document.querySelector('#eye-icon-' + fieldId);


            if (field && icon) {
                if (field.type === "password") {
                    field.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    field.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            } else {
                console.error('Field or icon not found for ID:', fieldId);
            }
        }

        // Function to validate password and confirm password match
        function validatePassword() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert('ពាក្យសម្ងាត់ថ្មី និងការបញ្ជាក់មិនដូចគ្នា។ សូមព្យាយាមម្ដងទៀត');
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // Attach validation to the form's submit event
        document.getElementById('passwordUpdateForm').onsubmit = function () {
            return validatePassword();
        };
    </script>
    <?php
            } else {
                echo "No staff found with the specified ID.";
            }

            $stmt->close();
        }
    }
    ?>

    <!-- Include your Bootstrap and jQuery dependencies for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
