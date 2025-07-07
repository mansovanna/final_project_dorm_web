<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/index.css">
    <title>Index</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            list-style: none;
            scroll-behavior: smooth;
            font-family: "khmer os siemreap";
        }

        .profile-pic {
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
            margin: 15px o 10px;
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

        /* @media screen and (min-width: 770px){
        .title_header h1 {
            font-size: 24px;
        }
        .title_header p {
            font-size: 16px;
        }
       
        .container-fluid .col-12{
            margin-top: -38px;
        }
        .sub-munu-wrap {
            top: 22%;
        }
    } */
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
    </style>
</head>