<?php 
    session_start();
    include("../include/header.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../style/header.css">
    <title>Edit Discipline</title>
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
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="content-wrapper">
    <h3 style="font-weight: bold; margin-bottom: 20px;">កែប្រែបទបញ្ញាផ្ទែក្នុង</h3>
    <?php
    include '../conn_db.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT text_content FROM discipline WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($text_content);
        $stmt->fetch();
        $stmt->close();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $text_content = $_POST['text_content'];

        $sql = "UPDATE discipline SET text_content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $text_content, $id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Content updated successfully.</div>';
        } else {
            echo '<div class="alert alert-danger">Failed to update content.</div>';
        }

        $stmt->close();
        $conn->close();
    }
    ?>
    <form action="update_discipline.php" method="post">
        <div class="form-group">
            <textarea name="text_content" id="text_content" class="form-control" rows="22"><?php echo htmlspecialchars($text_content); ?></textarea>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <button type="submit" class="btn btn-primary">រក្សាទុក</button>
    </form>
</div>
</body>
</html>
