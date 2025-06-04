<?php 
session_start();
include('include/header_student.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Discipline</title>
    <style>
        body {
            font-family: 'khmer os moul light';
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
           
        }
        h3 {
            font-family: 'khmer os moul light';
            text-align: center;
            margin-top: 0;
            padding-top: 10px;
            font-size: 25px;
        }
        .article-content {
            white-space: pre-wrap; 
            font-size: 17px;
            margin-top: 20px;
            font-family: 'khmer os siemreap';
             margin-top: 0px;
        }
        a {
            text-decoration: none;
        }
        .box-shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card box-shadow p-4">
        <h3 class="text-center" style="font-family: 'khmer os siemreap'; font-weight: bold;">បទបញ្ជាផ្ទៃក្នុង</h3>
        <div class="article-content">
            <?php
            include 'conn_db.php';
            $sql = "SELECT text_content FROM discipline";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p>" . htmlspecialchars($row['text_content']) . "</p>";
                }
            } else {
                echo "<p>មិនមានបទបញ្ជាផ្ទៃក្នុង</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>
</div>
<?php include('include/footer.php'); ?>  
</body>
</html>
