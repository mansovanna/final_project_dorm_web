<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Khmer OS Siemreap';
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            transition: transform 0.3s ease;
            transform: translateX(0);
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .sidebar.hide {
            transform: translateX(-100%);
        }
        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: blue;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-radius: 5px;
            transition: margin-left 0.3s ease;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            border-radius: 5px 5px 0 0;
            z-index: 1000;
            
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            left: 20px;
            cursor: pointer;
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar-toggle:hover {
            background-color: #495057;
        }
        .header .branch-expired {
            color: red;
        }
        .table-container {
            padding: 20px;
        }
        .btn-shadow {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: 'Khmer OS Siemreap', sans-serif;
            background-color: #f8f9fa;
        }
        .user-info {
            position: relative;
            display: inline-block;
        }
        .user-info a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: black;
        }
        .user-info img {
            border-radius: 50%;
            cursor: pointer;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 0.25rem;
            width: 280px;
            z-index: 1;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-menu .dropdown-header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .dropdown-menu .dropdown-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .dropdown-menu .dropdown-header h5 {
            margin: 3px;
        }
        .dropdown-menu .dropdown-divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 0;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #212529;
            white-space: nowrap;
        }
        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        .user-info:hover .dropdown-menu {
            display: block;
        }
        .content-wrapper.shift {
             transition: margin-left 0.3s ease;
        }
        .header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000; /* Ensures the header is above other elements */
        background-color: white; /* Background color to avoid transparency issues */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: Adds a shadow for better visibility */
        width: 100%; /* Ensure the header spans the full width of the page */
        height: 60px; /* Set height for the header */
        display: flex;
        align-items: center; /* Vertically center the content */
        padding: 0 20px;
        }

        .contener {
            margin-top: 80px;
        }
        @media (max-width: 767px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-toggle {
                display: block;
            }
            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="contener" id="contentWrapper">
        <div class="header">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <div class="branch-expired"></div>
            <div class="user-info">
            <a href="#" class="d-flex align-items-center">
                <?php
                    $imgSrc = isset($_SESSION['img']) && !empty($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : 'user1.png';
                ?>
                <img src="<?php echo $imgSrc; ?>" alt="Staff Picture" class="img-fluid rounded-circle" width="40">
            </a>
            <div class="dropdown-menu" id="userDropdown" style="left: -280px;">
                <div class="dropdown-header">
                    <?php
                        $imgSrc = isset($_SESSION['img']) && !empty($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : 'user1.png';
                    ?>
                    <img src="<?php echo $imgSrc; ?>" alt="Staff Picture" class="img-fluid rounded-circle">
                    <h5><?php echo $_SESSION['admin_username']; ?></h5>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="pf_staff.php" style="text-align: center;">ប្រវត្តិរូប</a>
                <a class="dropdown-item" href="logout.php" style="text-align: center;">ចាកចេញ</a>
            </div>
            </div>
        </div>
       
    </div>
    <div class="sidebar" id="sidebar">
        <div class="text-center p-3">
            <?php
                $imgSrc = isset($_SESSION['img']) && !empty($_SESSION['img']) ? htmlspecialchars($_SESSION['img']) : 'user1.png';
            ?>
            <img src="<?php echo $imgSrc; ?>" alt="Staff Picture"class=" rounded-circle" width="80" height="80" style="margin-bottom: 10px;">
                <h4><?php echo $_SESSION['admin_username']; ?></h4>
        </div>
        <a href="das_admin.php"><i class="fas fa-tachometer-alt"></i> ផ្ទាំងបង្ហាញព៍ត៌មាន</a>
        <a href="all_user.php"><i class="fas fa-users"></i> ទិន្នន័យនិស្សិត</a>
        <a href="rg_admin.php"><i class="fas fa-bed"></i> ទិន្នន័យនិស្សិតស្នើសុំស្នាក់នៅ</a>
        <a href="leave_ad.php"><i class="fas fa-calendar-alt"></i> ទិន្នន័យនិស្សិតស្នើសុំច្បាប់</a>
        <a href="no_pay.php"><i class="fas fa-dollar-sign"></i> ទិន្នន័យការបង់ប្រាក់</a>
        <a href="build.php"><i class="fa-solid fa-building"></i> អគារស្នាក់នៅ</a>
        <a href="#aboutUsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fa-solid fa-user-gear"></i></i> អំពីយើង</a>
        <ul class="collapse list-unstyled" id="aboutUsSubmenu">
            <li>
                <a href="data_staff.php"><i class="fa-solid fa-clipboard-user"></i> បុគ្គលិក</a>
            </li>
            <li>
                <a href="discipline.php"><i class="fa-regular fa-circle"></i> វិន័យអន្តេ</a>
            </li>
        </ul>
        <a href="#reportsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style=" margin-top: -12px;"><i class="fas fa-chart-bar"></i> របាយការណ៏</a>
        <ul class="collapse list-unstyled" id="reportsSubmenu">
            <li>
                <a href="register_report.php"><i class="fa-regular fa-circle"></i> បញ្ជីឈ្មោះនិស្សិតស្នាក់នៅ</a>
            </li>
            <li>
                <a href="report_leave.php"><i class="fa-regular fa-circle"></i> បញ្ជីឈ្មោះនិស្សិតសុំច្បាប់</a>
            </li>
            <li>
                <a href="report_pay.php"><i class="fa-regular fa-circle"></i> បញ្ជីឈ្មោះនិស្សិតបង់ថ្លៃស្នាក់នៅ</a>
            </li>
        </ul>
    </div>
<script>
    $(document).ready(function() {
        // Function to add 'active' class to the clicked link and remove from others
        $('.sidebar a').click(function() {
            $('.sidebar a').removeClass('active');
            $(this).addClass('active');
        });

        // Function to set 'active' class based on the current URL
        var currentUrl = window.location.href;
        $('.sidebar a').each(function() {
            if (this.href === currentUrl) {
                $(this).addClass('active');
            }
        });
        
        // Ensure the submenu stays open if a submenu link is active
        var activeSubmenuItem = $('#aboutUsSubmenu a.active');
        if (activeSubmenuItem.length) {
            $('#aboutUsSubmenu').addClass('show');
        }
        
        var activeReportsSubmenuItem = $('#reportsSubmenu a.active');
        if (activeReportsSubmenuItem.length) {
            $('#reportsSubmenu').addClass('show');
        }

        // Hide sidebar and dropdown menu when clicking outside
        $(document).mouseup(function(e) {
            var sidebar = $("#sidebar");
            var contentWrapper = $("#contentWrapper");
            var dropdownMenu = $(".dropdown-menu");

            // Hide sidebar if it's open and click is outside
            if (!sidebar.is(e.target) && sidebar.has(e.target).length === 0) {
                sidebar.removeClass('show');
            }

            // Hide dropdown menu if it's open and click is outside
            if (!dropdownMenu.is(e.target) && dropdownMenu.has(e.target).length === 0) {
                dropdownMenu.hide();
            }
        });

        // Toggle dropdown menu on user-info click
        $('.user-info').click(function(e) {
            e.stopPropagation();
            var dropdownMenu = $(this).find('.dropdown-menu');
            dropdownMenu.toggle();
        });

        // Toggle sidebar when sidebar toggle button is clicked
        $('#sidebarToggle').click(function() {
            $('#sidebar').toggleClass('show');
            $('#contentWrapper').toggleClass('shift');
        });
    });
    $(document).ready(function() {
        // Toggle the dropdown menu on click
        $('.user-info').click(function(e) {
            e.stopPropagation(); // Prevent event from bubbling up
            $('#userDropdown').toggleClass('show');
        });

        // Hide the dropdown menu when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.user-info').length) {
                $('#userDropdown').removeClass('show');
            }
        });
    });
</script>
</body>
</html>

