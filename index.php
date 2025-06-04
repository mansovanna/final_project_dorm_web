<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/index.css">
    <title>Index</title>
   
</head>
<body>
<header>
    <div class="container-fluid header-content">
        <div class="row align-items-center">
            <div class="col-12 col-md-3">
                <img src="img/logo.png" class="img-fluid mb-3" alt="Logo">
            </div>
            <div class="col-12 col-md-9 title">
                <h1>ប្រព័ន្ធគ្រប់គ្រងអន្តេវាសិកដ្ឋានសម្រាប់វិទ្យាស្ថានបច្ចេកវិទ្យាកំពង់ស្ពឺ</h1>
                <p>Dormitory Management System for Kampongspeu Institute of Technology</p>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-white mt-3">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#"><i class='bx bxs-home'></i> ទំព័រដើម</a>
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


    <div class="slider-container">
        <div class="slides">
            <div class="slide" style="background-image: url('img/sala.jpg');"></div>
            <div class="slide" style="background-image: url('img/dorm.jpg');"></div>
            <div class="slide" style="background-image: url('img/dorm_2.jpg');"></div>
            <div class="slide" style="background-image: url('img/room3.jpg');"></div>
            <div class="slide" style="background-image: url('img/room4.jpg');"></div>
            <div class="slide" style="background-image: url('img/room2.jpg');"></div>
            <div class="slide" style="background-image: url('img/room1.jpg');"></div>
        </div>
        <button class="prev" onclick="prevSlide()">&#10094;</button>
        <button class="next" onclick="nextSlide()">&#10095;</button>
    </div>
    <footer>
    <div class="container">
        <div class="row">
            <!-- Social Media Links -->
            <div class="col-md-4 box-footer-box">
                <h3>បណ្តាញសង្គម:</h3>
                <div class="box-footer-icon">
                    <i class='bx bxl-facebook'></i>Facebook: 
                    <a href="https://www.facebook.com/KsitCambodia/" style="text-indent: 5px;"> KsitCambodia</a>
                </div>
                <div class="box-footer-icon">
                    <i class='bx bxl-youtube'></i>YouTube:  
                    <a href="https://www.youtube.com/@kampongspeuinstituteoftech" style="text-indent: 10px;"> @kampongspeuinstituteoftech</a>
                </div>
            </div>
            <!-- Address -->
            <div class="col-md-4 box-footer-box">
                <h3>អាសយដ្ឋាន:</h3>
                <p>ផ្លូវជាតិលេខ៤៤ ភូមិអូរអង្គុំ ឃុំអមលាំង ស្រុកថ្ពង ខេត្តកំពង់ស្ពឺ</p>
            </div>
            <!-- Contact Info -->
            <div class="col-md-4 box-footer-box">
                <h3>ទំនាក់ទំនង:</h3>
                <div class="box-footer-icon">
                    <i class="bi bi-telegram"> Telegram:</i>
                    <p>085 483 609</p>
                </div>
                <div class="box-footer-icon">
                    <i class="bi bi-telephone-fill"> លេខទូរស័ព្ទ:</i>
                    <p> 085 483 609 / 010 770 774 </p>
                </div>
                <div class="box-footer-icon">
                    <i class="bi bi-envelope"> E-mail: </i>
                    <a href="mailto:info@ksit.edu.kh"> info@ksit.edu.kh,</a> <a href="mailto:bunhe@ksit.edu.kh"> bunhe@ksit.edu.kh</a>
                    
                </div>
            </div>
        </div>
    </div>
</footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        let slideIndex = 0;
        const slides = document.getElementsByClassName("slide");
        let timer = setInterval(nextSlide, 3000);

        function prevSlide() {
            showSlide(slideIndex -= 1);
            clearInterval(timer);
            timer = setInterval(nextSlide, 3000);
        }

        function nextSlide() {
            showSlide(slideIndex += 1);
            clearInterval(timer);
            timer = setInterval(nextSlide, 3000);
        }

        function showSlide(n) {
            if (n >= slides.length) { slideIndex = 0; }
            if (n < 0) { slideIndex = slides.length - 1; }
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex].style.display = "block";
        }

        showSlide(slideIndex);
    </script>
</body>
</html>
