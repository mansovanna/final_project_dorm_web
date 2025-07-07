<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

require_once('conn_db.php');
include('include/header_student.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Register</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/table.css">
</head>
<body>
    <div class="container">
        <div class="title text-left">
            <p  class="h4" style="font-weight: bold;">ប្រវត្តិការស្នាក់នៅ</p>
        </div>

        <div class="table-responsive p-3">
            <?php
            $history_first = mysqli_query($conn, "SELECT * FROM register WHERE student_id = '" . $_SESSION['user_id'] . "'");
            $history = mysqli_query($conn, "SELECT * FROM history WHERE student_id = '" . $_SESSION['user_id'] . "'");

            if (mysqli_num_rows($history_first) > 0 || mysqli_num_rows($history) > 0) {
                ?>
                <table class="table table-striped table-bordered">
                    <thead class="thead-success text-white">
                        <tr>
                            <th class="text-white">ល.រ</th>
                            <th class="text-white">លេខសម្គាល់និស្សិត</th>
                            <th class="text-white" colspan="2">ឈ្មោះនិស្សិត</th>
                            <th class="text-white">ជំនាញ</th>
                            <th class="text-white">កម្រិតសិក្សា</th>
                            <th class="text-white">ឆ្នាំ</th>
                            <th class="text-white">ថ្ងៃចូលស្នាក់នៅ</th>
                            <th class="text-white">អគារ</th>
                            <th class="text-white">លេខបន្ទប់</th>
                            <th class="text-white">លេខទូរស័ព្ទ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;

                        while ($data = mysqli_fetch_assoc($history_first)) {
                            ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $data['student_id']; ?></td>
                                <td colspan="2"><?= $data['lastname'] . ' ' . $data['name']; ?></td>
                                <td><?= $data['skill']; ?></td>
                                <td><?= $data['education_level']; ?></td>
                                <td><?= $data['year']; ?></td>
                                <td><?= $data['stay']; ?></td>
                                <td><?= $data['building']; ?></td>
                                <td><?= $data['room']; ?></td>
                                <td><?= $data['phone_student']; ?></td>
                            </tr>
                        <?php } ?>

                        <?php while ($result = mysqli_fetch_assoc($history)) { ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $result['student_id']; ?></td>
                                <td colspan="2"><?= $result['lastname'] . ' ' . $result['name']; ?></td>
                                <td><?= $result['skill']; ?></td>
                                <td><?= $result['education_level']; ?></td>
                                <td><?= $result['year']; ?></td>
                                <td><?= $result['change_date']; ?></td>
                                <td><?= $result['building']; ?></td>
                                <td><?= $result['room']; ?></td>
                                <td><?= $result['phone_student']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="text-center">
                    <p>No history found.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
</body>
</html>
