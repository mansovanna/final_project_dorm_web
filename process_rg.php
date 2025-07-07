<?php
session_start();

// Include database connection
include 'conn_db.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone_student = mysqli_real_escape_string($conn, $_POST['phone_student']);
    $phone_parent = mysqli_real_escape_string($conn, $_POST['phone_parent']);
    $skill = mysqli_real_escape_string($conn, $_POST['skill']);
    $education_level = mysqli_real_escape_string($conn, $_POST['education_level']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $status = 'រង់ចាំ'; // Default status

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload
    $img = $_FILES['img']['name'];
    $img_tmp = $_FILES['img']['tmp_name'];
    $img_folder = 'uploads/';
    $img_path = $img_folder . basename($img);

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($img_tmp, $img_path)) {

    } else {
        // Handle the error if the file upload fails
        die("Failed to upload image.");
    }

    // $stay = cunrrent date
    $stay = date('Y-m-d H:i:s');
    // Insert data into database
    $sql = "INSERT INTO register (student_id, password, lastname, name, username, gender, dob, address, phone_student, phone_parent, skill, education_level, year, img, status, stay) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssss", $student_id, $hashed_password, $lastname, $name, $username, $gender, $dob, $address, $phone_student, $phone_parent, $skill, $education_level, $year, $img_path, $status, $stay);

    if ($stmt->execute()) {
        // Redirect to a success page or show a success message
        echo "<script>
                alert('ការចុះឈ្មោះរបស់អ្នកទទួលបានជោគជ័យ!');
                window.location.href = 'rg.php';
              </script>";
        $stmt->close();
        $conn->close();
        exit();

    } else {
        // Handle the error if the query fails
        die("Database insertion failed: " . $stmt->error);
    }


}
?>