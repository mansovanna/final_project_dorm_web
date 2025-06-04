<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style_pf.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Profile</title>
    <style>
        .links li a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header Start -->
    <div class="header">
        <div class="logo"><img src="img/logo.png" alt="Logo"></div>
        <div class="header11">
            <p>ប្រព័ន្ធគ្រប់គ្រងអន្តេវាសិកដ្ឋានសម្រាប់វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</p>
            <p class="p1">Dormitory Management System for Kampongspeu institute of Technology</p>
        </div>
    </div>
    <nav>
        <div class="content">
            <ul class="links">
                <li><a href="index.php">ទំព័រដើម</a></li>
                <li>
                    <a href="#">អំពីអន្តេ <i class='bx bxs-down-arrow'></i></a>
                    <div class="sub-menu1">
                        <ul class="menu">
                            <li><a href="admin/staff_1.php">បុគ្គលិក</a></li>
                            <hr style=" margin: 0.1rem 0;border-top: 1px solid #e9ecef;">
                            <li><a href="data_dis.php">បទបញ្ជាផ្ទៃក្នុង</a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="rg.php">ចុះឈ្មោះស្នាក់នៅ</a></li>
                <div class="profile-box">
                <img src="<?php echo isset($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : 'img/user1.png'; ?>" class="profile-pic" onclick="toggleMenu()" alt="User Profile">
                </div>
            </ul>
        </div>
    </nav>
    <div class="sub-munu-wrap" id="subMenu">
        <div class="sub-menu">
            <div class="user-info">
            <img src="<?php echo isset($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : 'img/user1.png'; ?>" alt="User Image" > 
                <h4><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User name'; ?></h4>
            </div>
            <hr>
            <a href="show_pf.php" class="sub-menu-link">
                <img src="img/profile.png" alt="Profile Icon">
                <p>My Profile</p>
                <span></span>
            </a>
            <a href="his_rg.php" class="sub-menu-link">
                <img src="img/stay1.png" alt="Stay History Icon">
                <p>ប្រវត្តិស្នាក់នៅ</p>
                <span></span>
            </a>
            <a href="his_pay.php" class="sub-menu-link">
                <img src="img/pay1.png" alt="Payment History Icon">
                <p>ការបង់ថ្លៃស្នាក់នៅ</p>
                <span></span>
            </a>
            <a href="his_leave.php" class="sub-menu-link">
                <img src="img/leave1.png" alt="Leave History Icon">
                <p>ប្រវត្តិសុំច្បាប់</p>
                <span></span>
            </a>
            <a href="logout.php" class="sub-menu-link">
                <img src="img/logout.png" alt="Logout Icon">
                <p>ចាកចេញ</p>
                <span></span>
            </a>
        </div>
    </div>

    <script>
        let subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
    <!-- Header End -->
</body>
</html>
