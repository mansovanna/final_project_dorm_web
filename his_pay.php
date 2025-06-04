<?php
session_start();
require_once 'conn_db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}




$userId = $_SESSION['user_id'];

function getStudentPaymentStatusPerYear($conn, $userId)
{
    // Get student registration info
    $registerQuery = "SELECT * FROM register WHERE student_id = ?";
    $stmt = $conn->prepare($registerQuery);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $registerResult = $stmt->get_result();
    if (!$registerResult) {
        echo json_encode(['status' => 'error', 'message' => 'Student registration not found.']);
        exit();
    }

    $data = [];
    while ($row = $registerResult->fetch_assoc()) {
        $startYear = is_numeric($row['stay']) ? (int) $row['stay'] : (int) date('Y', strtotime($row['stay']));
        $currentYear = (int) date('Y');
        $years = [];
        for ($year = $startYear; $year <= $currentYear; $year++) {
            $years[] = $year;
        }
        foreach ($years as $year) {
            $paymentStatus = getPaymentStatusByYear($conn, $row['student_id'], $year);

            $data[$year] = [
                    'student_id' => $row['student_id'],
                    'lastname' => $row['lastname'],
                    'name' => $row['name'],
                    'building' => $row['building'],
                    'stay' => $row['stay'],
                    'skill' => $row['skill'],
                    'address' => $row['address'],
                    'education_level' => $row['education_level'],
                    'room' => isset($row['room']) ? $row['room'] : null,
                    'accommodation_fee' => isset($row['accommodation_fee']) ? (int) $row['accommodation_fee'] : 0,
                    'discount' => isset($row['discount']) ? (int) $row['discount'] : 0,
                    'water_fee' => isset($row['water_fee']) ? (int) $row['water_fee'] : 0,
                    'electricity_fee' => isset($row['electricity_fee']) ? (int) $row['electricity_fee'] : 0,
                    'total_fee' =>$sql['total_fee'] ?? 0,
                    'payment_status' => $paymentStatus['status'],
                    'payment_date' => $paymentStatus['date'],
                    'payment_id' => $paymentStatus['id'],
                    'year' => $year,
                ];
        }
    }
    return $data;
}

$data = getStudentPaymentStatusPerYear($conn, $userId);
// echo json_encode($data);
function getPaymentStatusByYear($conn, $student_id, $year)
{
    $sql = "SELECT id, status, date FROM payment 
            WHERE student_id = '" . mysqli_real_escape_string($conn, $student_id) . "' 
            AND `date` = '" . intval($year) . "'";

    $result = mysqli_query($conn, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        return [
            'status' => $row['status'],
            'date' => $row['date'] ?? null,
            'id' => $row['id'] ?? null,
        ];
    }

    return [
        'status' => null,
        'date' => null,
        'id' => null,
    ];
}

// required header
include 'include/header_student.php';

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/table.css">
</head>

<body>
    <div class="container">
        <div class="title mt-4 mb-3 text-center">
            <h3 style="font-weight: bold; text-align: left;">ការបង់ថ្លៃស្នាក់នៅ</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>អត្ថលេខនិស្សិត</th>
                        <th>ឈ្មោះនិស្សិត</th>
                        <th>អគារ</th>
                        <th>បន្ទប់</th>
                        <th>ថ្លៃស្នាក់នៅ</th>
                        <th>បញ្ចុះតម្លៃ</th>
                        <th>ថ្លៃទឺក</th>
                        <th>ថ្លៃភ្លើង</th>
                        <th>តម្លៃសរុប</th>
                        <th>ថ្ងៃ/ខែ/បង់ថ្លៃស្នាក់នៅ</th>
                        <th>សកម្មភាព</th>
                        <th>សកម្មភាពបង់ប្រាក់</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($data as $year => $register):
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($userId); ?></td>
                            <td><?= htmlspecialchars($register['lastname'] . ' ' . $register['name']); ?></td>
                            <td><?= htmlspecialchars($register['building'])?? "-"; ?></td>
                            <td><?= $register ? htmlspecialchars($register['room']) : '-'; ?></td>
                            <td><?= $register ? number_format($register['accommodation_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $register ? htmlspecialchars($register['discount']) . '%' : '-'; ?></td>
                            <td><?= $register ? number_format($register['water_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $register ? number_format($register['electricity_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $register ? number_format($register['total_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $register ? date('d/m/Y', strtotime($register['payment_date'])) : '-'; ?></td>
                            <td>
                                <?php
                                if ($register) {
                                    switch ($register['payment_status']) {
                                        case 'Pending':
                                            echo '<span class="badge badge-warning p-2 text-white">⏳ កំពុងរងចាំមិនពិនិត្យ</span>';
                                            break;
                                        case 'Approved':
                                            echo '<span class="badge badge-success p-2 text-white">✔ បានបង់រួចរាល់</span>';
                                            break;
                                        case 'Rejected':
                                            echo '<span class="badge badge-danger p-2">❌ បដិសេធ</span>';
                                            break;
                                        default:
                                            echo '<span class="badge badge-secondary p-2">មិនទាន់បានបង់ - ឆ្នាំ ' . $year . '</span>';
                                            break;
                                    }
                                } else {
                                    echo '<span class="badge badge-primary p-2">បង់ប្រាក់ឥឡូវនេះ - ឆ្នាំ ' . $year . '</span>';
                                }
                                ?>
                            </td>

                            <td>
                                <?php if (!$register || $register['payment_status'] === 'Rejected' || $register['payment_status'] === null): ?>
                                    <a href="payment_form.php?student_id=<?= urlencode($userId); ?>&year=<?= $year; ?>"
                                        class="btn btn-sm btn-primary">បង់ប្រាក់ឥឡូវនេះ</a>
                                <?php elseif($register && $register['payment_status'] === 'Approved' || $register['payment_status'] === 'Pending'): ?>
                                   
                                        <!-- បន្ថែមប៊ូតុង "មើលបន្ថែម" ដែលបង្ហាញ popup -->
                                        <button type="button" class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#paymentDetailModal<?= $register['payment_id']; ?>">
                                            មើលបន្ថែម
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="paymentDetailModal<?= $register['payment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="paymentDetailLabel<?= $register['payment_id']; ?>" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="paymentDetailLabel<?= $register['payment_id']; ?>">ព័ត៌មានបង់ប្រាក់</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                <p><strong>លេខបង់ប្រាក់:</strong> <?= htmlspecialchars($register['payment_id']); ?></p>
                                                <p><strong>ឆ្នាំ:</strong> <?= htmlspecialchars($register['year']); ?></p>
                                                <p><strong>ថ្ងៃបង់ប្រាក់:</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($register['payment_date']))); ?></p>
                                                <p><strong>ស្ថានភាព:</strong>
                                                    <?php
                                                        switch ($register['payment_status']) {
                                                            case 'Pending':
                                                                echo '<span class="badge badge-warning p-2 text-white">⏳ កំពុងរងចាំមិនពិនិត្យ</span>';
                                                                break;
                                                            case 'Approved':
                                                                echo '<span class="badge badge-success p-2 text-white">✔ បានបង់រួចរាល់</span>';
                                                                break;
                                                            case 'Rejected':
                                                                echo '<span class="badge badge-danger p-2">❌ បដិសេធ</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge badge-secondary p-2">មិនទាន់បានបង់</span>';
                                                                break;
                                                        }
                                                    ?>
                                                </p>
                                                <hr>
                                                <p><strong>ថ្លៃស្នាក់នៅ:</strong> <?= number_format($register['accommodation_fee'], 0) . ' ៛'; ?></p>
                                                <p><strong>បញ្ចុះតម្លៃ:</strong> <?= htmlspecialchars($register['discount']) . '%'; ?></p>
                                                <p><strong>ថ្លៃទឺក:</strong> <?= number_format($register['water_fee'], 0) . ' ៛'; ?></p>
                                                <p><strong>ថ្លៃភ្លើង:</strong> <?= number_format($register['electricity_fee'], 0) . ' ៛'; ?></p>
                                                <p><strong>តម្លៃសរុប:</strong> <?= number_format($register['total_fee'], 0) . ' ៛'; ?></p>
                                                <hr>
                                                <?php
                                                // Fetch payment image if exists
                                                $paymentImage = null;
                                                if (!empty($register['payment_id'])) {
                                                    $imgSql = "SELECT image FROM payment WHERE id = ?";
                                                    $imgStmt = $conn->prepare($imgSql);
                                                    $imgStmt->bind_param("i", $register['payment_id']);
                                                    $imgStmt->execute();
                                                    $imgResult = $imgStmt->get_result();
                                                    
                                                    if ($imgResult && $imgRow = $imgResult->fetch_assoc()) {
                                                        $paymentImage = $imgRow['image'] ?? null;
                                                        if ($paymentImage) {
                                                            $paymentImage = 'http://localhost/dorm_ksit/uploads/images_qr/' . $paymentImage; // Assuming images are stored in 'uploads' directory
                                                        }
                                                    }
                                                }
                                                ?>
                                                <p><strong>វិក័យប័ត្របង់ប្រាក់:</strong></p>
                                                <?php if ($paymentImage): ?>
                                                    <img src="<?= htmlspecialchars($paymentImage); ?>" alt="រូបភាពបង់ប្រាក់" class="img-fluid mb-2" style="max-width:100%;height:auto;">
                                                <?php else: ?>
                                                    <span class="text-muted">មិនមានរូបភាពបង់ប្រាក់</span>
                                                <?php endif; ?>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">បិទ</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include('include/footer.php'); ?>
</body>

</html>