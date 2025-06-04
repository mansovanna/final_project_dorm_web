<?php 
session_start();
include("../include/header.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include "../conn_db.php";

    // Function to sanitize input
    function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    // Prepare and bind the data
    $staffName = sanitize($_POST['staff_Name']);
    $username = sanitize($_POST['username']);
    $phone = sanitize($_POST['phone_number']);
    $email = sanitize($_POST['Email']);
    $password = password_hash(sanitize($_POST['password']), PASSWORD_BCRYPT);
    // Check if file is uploaded successfully
    if(isset($_FILES["img"]) && $_FILES["img"]["error"] == 0) {
        // Set upload directory
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["img"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["img"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "<script>alert('File is not an image.');</script>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["img"]["size"] > 500000) {
            echo "<script>alert('Sorry, your file is too large.');</script>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif", "JPJ");
        if(!in_array($imageFileType, $allowedFormats)) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                // Prepare SQL statement
                $sql = "INSERT INTO staff (staff_Name, username, phone_number, Email, img, password) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $staffName, $username, $phone, $email, $target_file, $password);

                // Execute the statement
                if ($stmt->execute() === TRUE) {
                    echo "<script>alert('New record created successfully');</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                // Close statement
                $stmt->close();
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
        // Close connection
        $conn->close();
    } else {
        echo "<script>alert('No file uploaded or an error occurred during upload.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Staff</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
</head>
<body>

    <div class="content-wrapper">
        <div class="d-flex justify-content-center">
            <div class="col-lg-10">
                <h3 style="font-weight: bold; margin-bottom: 20px;">បន្ថែមទិន្ន័យបុគ្គលិក</h3>
                <div class="button mt-12 mb-3">
                    <a href="data_staff.php" class="btn btn-default btn-secondary"><i class="fas fa-share fa-flip-horizontal fa-fw"></i> ត្រឡប់</a>
                </div>
            </div>
        </div>
            <div class="d-flex justify-content-center">
                <div class="card card-primary card-outline col-lg-10">
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="staff_Name">ឈ្មោះ</label>
                                        <input type="text" name="staff_Name" class="form-control" id="staff_Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="username">អក្សរឡាតាំង</label>
                                        <input type="text" name="username" class="form-control" id="username" placeholder="USER NAME" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">លេខសម្ងាត់:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                                
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">លេខទូរស័ព្ទ</label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="0xx-xxx-xxx" required>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 45px">
                                        <label for="Email">សារអេឡិចត្រូនិក</label>
                                        <input type="email" name="Email" class="form-control" id="Email" placeholder="username@gmail.com" required>
                                    </div>
                                    <div class="form-group" style=" margin-top: -25px;">
                                        <label for="img">រូបភាព</label>
                                        <input type="file" name="img" class="form-control" id="img" accept="image/*" required>
                                    </div>
                                    <div class="col-12  d-flex justify-content-end"> 
                                        <button type="submit" name="submit" class="btn btn-primary">រក្សាទុក</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

