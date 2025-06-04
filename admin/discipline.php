<?php
// Start the session
session_start();
?>
<?php include("../include/header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
    <title>Discipline</title>
    <style>
        body {
            font-family: 'Khmer OS Siemreap';
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        h3 {
            font-family: 'Khmer OS Siemreap';
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
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">បទបញ្ជាផ្ទៃក្នុង</h3>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="article-content">
                <?php
                include '../conn_db.php';
                $sql = "SELECT id, text_content FROM discipline";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<p>" . htmlspecialchars($row['text_content']) . "</p>";
                        echo '<a href="update_discipline.php?id=' . $row['id'] . '" class="btn btn-primary"><i class="fa-solid fa-edit fa-fw"></i> កែសម្រួល</a>';
                    }
                } else {
                    echo "<p>មិនមានបទបញ្ញាផ្ទៃក្នុង!</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
