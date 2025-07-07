<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'conn_db.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM register WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ព័ត៌មានផ្ទាល់ខ្លួន</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@900&family=Koulen&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include('include/header_student.php'); ?>

        <!-- Main Content -->
        <main class="container mt-5">
            <div class="row justify-content-center">
                <div class="border rounded p-3 shadow-lg bg-white">
                    <div class="row p-3">
                        <!-- Profile Image & Actions -->
                        <div class="col-md-4 text-center">
                            <img src="<?= $user['img'] ?: 'img/user1.png' ?>" alt="Profile"
                                 class="img-thumbnail rounded-circle shadow-sm mb-3 profile-img">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="edit_pf.php" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square me-1"></i> កែប្រវត្តិរូប
                                </a>
                                <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="bi bi-key me-1"></i> ប្តូរពាក្យសម្ងាត់
                                </button>
                            </div>
                        </div>

                        <!-- Profile Info Table -->
                        <div class="col-md-8">
                            <h5 class="mb-3 border-bottom pb-2 text-primary fw-bold">
                                <i class="bi bi-person-lines-fill me-2"></i>ព័ត៌មានផ្ទាល់ខ្លួន
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>លេខសម្គាល់និស្សិត:</strong> <?= htmlspecialchars($user['student_id']) ?></div>
                                <div class="col-md-6 mb-2"><strong>នាមត្រកូល នាមខ្លួន:</strong> <?= htmlspecialchars($user['lastname'] . ' ' . $user['name']) ?></div>
                                <div class="col-md-6 mb-2"><strong>អក្សរឡាតាំង:</strong> <?= htmlspecialchars($user['username']) ?></div>
                                <div class="col-md-6 mb-2"><strong>ភេទ:</strong> <?= htmlspecialchars($user['gender']) ?></div>
                                <div class="col-md-6 mb-2"><strong>ថ្ងៃខែឆ្នាំកំណើត:</strong> <?= htmlspecialchars($user['dob']) ?></div>
                                <div class="col-md-6 mb-2"><strong>អាសយដ្ឋាន:</strong> <?= htmlspecialchars($user['address']) ?></div>
                                <div class="col-md-6 mb-2"><strong>លេខទូរស័ព្ទនិស្សិត:</strong> <?= htmlspecialchars($user['phone_student']) ?></div>
                                <div class="col-md-6 mb-2"><strong>លេខទូរស័ព្ទអាណាព្យាបាល:</strong> <?= htmlspecialchars($user['phone_parent']) ?></div>
                                <div class="col-md-6 mb-2"><strong>ជំនាញ:</strong> <?= htmlspecialchars($user['skill']) ?></div>
                                <div class="col-md-6 mb-2"><strong>កម្រិតសិក្សា:</strong> <?= htmlspecialchars($user['education_level']) ?></div>
                                <div class="col-md-6 mb-2"><strong>ឆ្នាំសិក្សា:</strong> <?= htmlspecialchars($user['year']) ?></div>
                                <div class="col-md-6 mb-2"><strong>ថ្ងៃចូលស្នាក់នៅ:</strong> <?= htmlspecialchars($user['stay']) ?></div>
                                <div class="col-md-6 mb-2"><strong>អាគារ:</strong> <?= htmlspecialchars($user['building']) ?></div>
                                <div class="col-md-6 mb-2"><strong>បន្ទប់:</strong> <?= htmlspecialchars($user['room']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Modal: Change Password -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" action="change_password_student.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">ប្តូរពាក្យសម្ងាត់</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

                        <div class="mb-3">
                            <label for="current_password" class="form-label">ពាក្យសម្ងាត់បច្ចុប្បន្ន</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-primary">ប្តូរ</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center text-muted py-3 mt-auto">
            <?php include('include/footer.php'); ?>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
