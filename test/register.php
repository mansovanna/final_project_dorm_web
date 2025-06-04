<?php 
session_start();
include 'conn_db.php';

// Define the function before using it
function requstRoom($conn, $student_id) {
    $status = 'Pedding';
    $stmt = $conn->prepare("INSERT INTO request_room (student_id, status) VALUES (?, ?)");
    $stmt->bind_param('is', $student_id, $status);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $student_id      = (int)$_POST['student_id'];
    $password        = mysqli_real_escape_string($conn, $_POST['password']);
    $first_name      = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name       = mysqli_real_escape_string($conn, $_POST['last_name']);
    $user_name       = mysqli_real_escape_string($conn, $_POST['user_name']);
    $gender          = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_birth      = mysqli_real_escape_string($conn, $_POST['date_birth']);
    $address         = mysqli_real_escape_string($conn, $_POST['address']);
    $department      = mysqli_real_escape_string($conn, $_POST['department']);
    $education_level = mysqli_real_escape_string($conn, $_POST['education_level']);
    $year            = (int)$_POST['year'];
    $phone_student   = mysqli_real_escape_string($conn, $_POST['phone_student']);
    $phone_parent    = mysqli_real_escape_string($conn, $_POST['phone_parent']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $img = '';
    $old_img_path = '';

    // Check if student exists
    $check_student = $conn->prepare("SELECT image_profile FROM students WHERE student_id = ?");
    $check_student->bind_param('i', $student_id);
    $check_student->execute();
    $check_result = $check_student->get_result();
    $student_exists = $check_result->num_rows > 0;

    if ($student_exists) {
        $student_data = $check_result->fetch_assoc();
        $old_img_path = $student_data['image_profile'];
    }

    // Handle file upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img_folder = 'uploads/';
        if (!is_dir($img_folder)) {
            mkdir($img_folder, 0755, true);
        }

        $file_ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 2 * 1024 * 1024; // 2MB

        if (in_array(strtolower($file_ext), $allowed_ext) && $_FILES['img']['size'] <= $max_file_size) {
            if (!empty($old_img_path) && file_exists($old_img_path)) {
                unlink($old_img_path);
            }

            $img_name = uniqid('', true) . '.' . $file_ext;
            $img_path = $img_folder . $img_name;

            if (move_uploaded_file($_FILES['img']['tmp_name'], $img_path)) {
                $img = $img_path;
            }
        } else {
            $_SESSION['error'] = "Invalid image file or file too large (max 2MB)";
            header("Location: rg.php");
            exit();
        }
    } else {
        $img = $old_img_path;
    }

    // Insert or Update
    if ($student_exists) {
        // Skip password update
        $stmt = $conn->prepare("UPDATE students SET 
            first_name = ?, last_name = ?, user_name = ?, 
            gender = ?, date_birth = ?, address = ?, department = ?, 
            education_level = ?, year = ?, phone_student = ?, phone_parent = ?, 
            image_profile = ? WHERE student_id = ?");
        
        $stmt->bind_param(
            'ssssssssisssi',
            $first_name, $last_name, $user_name,
            $gender, $date_birth, $address, $department,
            $education_level, $year, $phone_student, $phone_parent,
            $img, $student_id
        );
    } else {
        // Insert new student with password
        $stmt = $conn->prepare("INSERT INTO students (
            student_id, password, first_name, last_name, user_name,
            gender, date_birth, address, department, education_level, year,
            phone_student, phone_parent, image_profile
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            'isssssssssisss',
            $student_id, $hashed_password, $first_name, $last_name, $user_name,
            $gender, $date_birth, $address, $department, $education_level,
            $year, $phone_student, $phone_parent, $img
        );
    }

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful!";

        if (!$student_exists) {
            requstRoom($conn, $student_id);
        }

    } else {
        if (!empty($img) && $img !== $old_img_path && file_exists($img)) {
            unlink($img);
        }
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: rg.php");
    exit();
}
?>
