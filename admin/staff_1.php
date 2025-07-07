<?php
session_start();
include "../conn_db.php";

$sql = "SELECT * FROM staff WHERE id != 81";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="../style/style_pf.css">
        <link rel="stylesheet" href="../style/style_staff.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../style/index.css">
        <title>Document</title>
        <style>
            body {
                background-color: #f3f5f9;
                font-family: Arial, sans-serif;
            }

            a {
                text-decoration: none;
            }

            /* Modal styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.8);
                overflow: hidden;
            }

            .modal-content {
                margin: 10% auto;
                display: block;
                width: 26%;
                max-width: 500px;
                overflow: hidden;
            }

            .close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #fff;
                font-size: 40px;
                font-weight: bold;
                cursor: pointer;
            }

            .close:hover,
            .close:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }

            @media (max-width: 768px) {
                .modal-content {
                    width: 95%;
                    max-width: 300px;
                    margin: 50% auto;
                }
            }

            .profile-pic {
                background: none;
                width: 45px;
                border-radius: 50%;
                padding: 6px;
                margin-top: -28px;
                position: absolute;
                /* z-index: 9999; */
            }

            .sub-munu-wrap {
                position: absolute;
                left: 68%;
                top: 36%;
                width: 320px;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.5s;
                z-index: 100;
            }

            .sub-munu-wrap.open-menu a {
                text-decoration: none;
            }

            .sub-munu-wrap.open-menu {
                max-height: 425px;

            }

            .sub-menu {
                background: #fff;
                box-shadow: 0px 0px 2px rgb(14, 15, 14);
                padding: 20px;
                margin: 10px;
                border-radius: 10px;
            }

            .user-info {
                display: flex;
                align-items: center;
            }

            .user-info h3 {
                font-weight: 500;
            }

            .user-info img {
                width: 60px;
                border-radius: 50%;
                margin-right: 15px;
            }

            .sub-menu hr {
                border: 0;
                height: 1px;
                width: 100%;
                background: #ccc;
                margin: 15px 0 10px;
            }

            .sub-menu-link {
                display: flex;
                align-items: center;
                text-decoration: none;
                color: #525252;
                margin: 12px 0;
            }

            .sub-menu-link p {
                width: 100%;
            }

            .sub-menu-link img {
                width: 40px;
                background: #e5e5e5;
                border-radius: 50%;
                padding: 8px;
                margin-right: 15px;
            }

            .sub-menu-link span {
                font-size: 22px;
                transform: transform 0.5;
            }

            .sub-menu-link:hover span {
                transform: translateX(5px)
            }

            .sub-menu-link:hover p {
                font-weight: 600;
            }

            .sub-menu1 {
                display: none;
                position: absolute;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                z-index: 100;
            }

            .sub-menu1 ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .sub-menu1 ul li:hover {
                background-color: #f0f0f0;
            }

            .links li:hover .sub-menu1 {
                display: block;
            }

            @media screen and (min-width: 768px) {
                .profile-pic {
                    left: 90%;
                }

                .footer .box-footer-box {
                    width: auto;
                    padding: 20px;
                }
            }

            @media screen and (max-width: 767px) {
                .navbar .container {
                    margin-left: 15px;
                }

                .title_header h1 {
                    font-size: 10px;
                }

                .profile-pic {
                    width: 45px;
                    border-radius: 50%;
                    padding: 6px;
                    margin-top: 0 !important;
                    position: relative;
                }

                .sub-munu-wrap {
                    left: auto;
                    right: 16px;
                    width: 90%;
                }

                .navbar-nav .dropdown-menu {
                    margin-right: 15px;
                }

            }

            @media screen and (max-width: 900px) and (min-width: 600px),
            (max-width: 992px) {
                .sub-munu-wrap {
                    left: auto;
                    right: 10px;
                    top: 16%;
                }

            }

            @media only screen and (width: 768px) and (height: 1024px) {
                .title_header h1 {
                    font-size: 22px;
                }

                .title_header p {
                    font-size: 14px;
                }

                .container-fluid .col-12 {
                    margin-top: -38px;
                }

                .sub-munu-wrap {
                    top: 26%;
                }
            }

            @media screen and (max-width: 767px) {
                .col-12 img {
                    max-width: 60px;
                    position: absolute;
                    right: 80%;
                    top: -15px;
                }
            }
        </style>
    </head>

    <body>
        <header>
            <div class="container-fluid header-content">
                <div class="row align-items-center">
                    <div class="col-12 col-md-3">
                        <img src="../img/logo.png" class="img-fluid mb-3" alt="Logo">
                    </div>
                    <div class="col-12 col-md-9 title_header">
                        <h1>ប្រព័ន្ធគ្រប់គ្រងអន្តេវាសិកដ្ឋានសម្រាប់វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</h1>
                        <p>Dormitory Management System for Kampongspeu Institute of Technology</p>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-white">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="profile-box">
                        <picture>
                            <source srcset="<?php echo $user['img'] ?: 'img/user1.png'; ?>" type="image/svg+xml">
                            <img src="<?php echo isset($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : '../img/user1.png'; ?>"
                                class="profile-pic" onclick="toggleMenu()" class="img-fluid img-thumbnail rounded-circle"
                                alt="Profile" style="width: 150px; height: 150px; object-fit: cover;">
                        </picture>

                    </div>
                    <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="../index.php"><i class='bx bxs-home'></i> ទំព័រដើម</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    អំពីអន្តេវាសិកដ្ឋាន
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="../admin/staff_1.php">បុគ្គលិក</a>
                                    <hr style=" margin: 0.1rem 0;border-top: 1px solid #e9ecef;">
                                    <a class="dropdown-item" href="../data_dis.php">បទបញ្ជាផ្ទៃក្នុង</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../rg.php">ចុះឈ្មោះស្នាក់នៅ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../login.php">ចូលគណនី</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <div class="sub-munu-wrap" id="subMenu">
            <div class="sub-menu">
                <div class="user-info">
                    <img src="<?php echo isset($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : '../img/user1.png'; ?>"
                        alt="User Image">
                    <h4><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User name'; ?>
                    </h4>
                </div>
                <hr>
                <a href="../show_pf.php" class="sub-menu-link">
                    <img src="../img/profile.png" alt="Profile Icon">
                    <p>My Profile</p>
                    <span></span>
                </a>
                <a href="../his_rg.php" class="sub-menu-link">
                    <img src="../img/stay1.png" alt="Stay History Icon">
                    <p>ប្រវត្តិស្នាក់នៅ</p>
                    <span></span>
                </a>
                <a href="../his_pay.php" class="sub-menu-link">
                    <img src="../img/pay1.png" alt="Payment History Icon">
                    <p>ការបង់ថ្លៃស្នាក់នៅ</p>
                    <span></span>
                </a>
                <a href="../his_leave.php" class="sub-menu-link">
                    <img src="../img/leave1.png" alt="Leave History Icon">
                    <p>ប្រវត្តិសុំច្បាប់</p>
                    <span></span>
                </a>
                <a href="../logout.php" class="sub-menu-link">
                    <img src="../img/logout.png" alt="Logout Icon">
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
            document.addEventListener('click', function (event) {
                const isClickInside = subMenu.contains(event.target) || event.target.classList.contains('profile-pic');

                if (!isClickInside && subMenu.classList.contains('open-menu')) {
                    subMenu.classList.remove('open-menu');
                }
            });
            // Modal scripts
            function openModal(imgSrc) {
                var modal = document.getElementById("imgModal");
                var modalImg = document.getElementById("modalImg");
                modal.style.display = "block";
                modalImg.src = imgSrc;
            }
            function closeModal() {
                var modal = document.getElementById("imgModal");
                modal.style.display = "none";
                document.body.style.overflow = 'auto';  // Restore background scrolling
            }

            // Close modal when clicking outside the image content
            window.onclick = function (event) {
                var modal = document.getElementById("imgModal");
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>

        <!-- Image Modal -->
        <div id="imgModal" class="modal">
            <span class="close" onclick="closeModal()">&times;</span>
            <img class="modal-content" id="modalImg">
        </div>

        <!-- Header End -->
        <div class="team-section">
            <h4 style=" font-weight: bold;">គណកម្មការគ្រប់គ្រងអន្តេវាសិកដ្ឋាន</h4>
            <hr style="width:80%;text-align:center;margin-left:auto;margin-right:auto;border: 2px solid rgb(0, 71, 212);">
            <div class="container">
                <div class="row">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="team-member">
                                <img src='<?php echo $row['img']; ?>' alt='Staff Image' class="img-fluid"
                                    onclick="openModal('<?php echo $row['img']; ?>')">
                                <h5><?php echo $row['staff_Name']; ?></h5>
                                <!-- <?php if ($row['username'] != 'BOUR SOKKHORN'): ?>
                            <p class="card-text text-uppercase mb-1">ជំនាញ: <?php echo htmlspecialchars($row['staff_Name']); ?></p>
                        <?php else: ?>
                            <p class="card-text text-uppercase mb-1">តួនាទី: <?php echo htmlspecialchars($row['staff_Name']); ?></p>
                        <?php endif; ?> -->
                                <i class="bi bi-telephone-fill">លេខទូរស័ព្ទ</i>
                                <a href="tel:<?php echo $row['phone_number']; ?>">
                                    <?php echo $row['phone_number']; ?>
                                </a>
                                <br>
                                <i class='bx bxs-envelope'></i>
                                <a href="mailto:<?php echo $row['Email']; ?>">
                                    <?php echo $row['Email']; ?>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
} else {
    echo "0 results";
}
$conn->close();
?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include('../include/footer.php'); ?>
</body>

</html>