<?php
if (isset($_SESSION['message'])) {
    echo "<div class='alert success'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert error'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style_pf.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="style/index.css">
    <title>Register</title>
</head>
<style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "khmer os siemreap";
            
        }
        a:link{
            text-decoration: none; 
        }
    .container_body {
            margin-top: 4rem; 
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem; 
            background-color: #ffffff;
            border-radius: 10px; 
        }
        .form-control {
            margin-bottom: 1rem; 
        }
        .btn-primary {
            background-color: #007bff;
            border: none; 
            color: #fff;
            padding: 0.5rem 1rem; 
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
        .container_body{
            margin-bottom: 40px;
        }
    /* .footer{
        position: absolute;
        top: 120%;
    }    */
    @media (max-width: 768px) {
        .regis {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
       
        .row{
            width: 65%;
        }
        .regis h2{
            font-size: 22px;
        }
    }
    @media screen and (max-width: 767px) {
        .col-12 img {
            max-width: 60px;
            position: absolute;
            right: 65%;
            top: -25px;
        }
        .col-12 h1 {
            font-size: 10px;
            color: #fff;
            margin-left: 40%;
            position: absolute;
            text-overflow: ellipsis; 
            overflow: hidden; 
            white-space: nowrap;
        }
        .col-12 p {
            font-size: 9px;
            color: #fff;
            margin-left: 30%;
            margin-top: 20px;
            position: absolute;
            text-overflow: ellipsis; 
            overflow: hidden; 
            white-space: nowrap;

        }
    }
</style>
<body>
    <!-- Header Start -->
<header>
    <div class="container-fluid header-content"> 
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <img src="img/logo.png" class="img-fluid mb-3" alt="Logo">
            </div>
            <div class="col-12 col-md-9 title_header">
                <h1>ប្រព័ន្ធគ្រប់គ្រងអន្តេវាសិកដ្ឋានសម្រាប់វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</h1>
                <p>Dormitory Management System for Kampongspeu Institute of Technology</p>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-white">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php"><i class='bx bxs-home'></i> ទំព័រដើម</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            អំពីអន្តេវាសិកដ្ឋាន
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="admin/staff_1.php">បុគ្គលិក</a>
                            <hr style=" margin: 0.1rem 0;border-top: 1px solid #e9ecef;">
                            <a class="dropdown-item" href="data_dis.php">បទបញ្ជាផ្ទៃក្នុង</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rg.php">ចុះឈ្មោះស្នាក់នៅ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">ចូលគណនី</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

  <!-- Header End -->

  

<div class="container container_body mt-4">
    <div class="row" >
        <div class="col-md-6">
            <div class="regis">
                <h2>ចុះឈ្មោះស្នាក់នៅ</h2>
            </div>
            <form action="process_rg.php" method="POST" enctype="multipart/form-data">
            <!-- <form action="process_rg.php" method="POST" onsubmit="showPopup()" > -->
                <div class="row" style="width:200%;">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="student_id">លេខសម្គាល់និស្សិត:</label>
                            <input type="text" name="student_id" id="student_id" class="form-control" placeholder="221082007" required>
                        </div>
                        <div class="form-group">
                            <label for="password">លេខសម្ងាត់:</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label for="lastname">គោត្តនាម:</label>
                            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="User" required>
                        </div>
                        <div class="form-group">
                            <label for="name">នាម:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="username">អក្សរឡាតាំង:</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="USERNAME" required>
                        </div>
                        <div class="form-group">
                            <label style="margin-bottom: 20px;">ភេទ:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="ប្រុស">
                                <label  class="form-check-label" for="male">ប្រុស</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="ស្រី">
                                <label class="form-check-label" for="female">ស្រី</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dob">ថ្ងៃខែឆ្នាំកំណើត:</label>
                            <input type="date" name="dob" id="dob" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="address">អាសយដ្ឋាន:</label>
                            <textarea name="address" id="address" class="form-control" placeholder="ភូមិ ឃុំ ស្រុក/ខណ្ឌ ខេត្ត/ក្រុង " style="height: 40px;" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="row" style="width:200%;">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone_student">លេខទូរស័ព្ទនិស្សិត:</label>
                            <input type="number" name="phone_student" id="phone_student" class="form-control" placeholder="0xx-xxx-xxx" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="phone_parent">លេខទូរស័ព្ទអាណាព្យាបាល:</label>
                            <input type="number" name="phone_parent" id="phone_parent" class="form-control" placeholder="0xx-xxx-xxx" required>
                        </div>
                    </div>
                </div>
                <div class="row" style="width:200%;">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="skill">ជំនាញ:</label>
                            <select name="skill" id="skill" class="form-control" required>
                                <option>ជ្រើសរើសជំនាញ</option>
                                <option>បច្ចេកវិទ្យាកុំព្យូទ័រ</option>
                                <option>វិទ្យាសាស្ត្រដំណាំ</option>
                                <option>គីមីចំណីអាហារ</option>
                                <option>បច្ចេកវិទ្យាអគ្គីសនី</option>
                                <option>មេកានិច</option>
                                <option>វិទ្យាសាស្ត្រសត្វ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="education_level">កម្រិតវប្បធម៌:</label>
                            <select name="education_level" id="education_level" class="form-control" required>
                                <option>បរិញ្ញាបត្រ</option>
                                <option>បរិញ្ញាបត្ររង</option>
                                <option>៩+៣</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" style="width:200%;">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="year">ឆ្នាំទី:</label>
                            <select name="year" id="year" class="form-control" required>
                                <option value="1">១</option>
                                <option value="2">២</option>
                                <option value="3">៣</option>
                                <option value="4">៤</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="img">ជ្រើសរើសរូបភាព:</label>
                            <input type="file" name="img" id="img" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="ចុះឈ្មោះ" class="btn btn-primary">
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php     

include ('include/footer.php')


?>