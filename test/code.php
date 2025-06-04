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
                    for ($year = $startYear; $year <= $currentYear; $year++):
                        $payment = $paymentsByYear[$year] ?? null;
                        ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= htmlspecialchars($userId); ?></td>
                            <td><?= htmlspecialchars($register['lastname'] . ' ' . $register['name']); ?></td>
                            <td><?= htmlspecialchars($register['building']); ?></td>
                            <td><?= $payment ? htmlspecialchars($payment['room_number']) : '-'; ?></td>
                            <td><?= $payment ? number_format($payment['accommodation_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $payment ? htmlspecialchars($payment['discount']) . '%' : '-'; ?></td>
                            <td><?= $payment ? number_format($payment['water_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $payment ? number_format($payment['electricity_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $payment ? number_format($payment['total_fee'], 0) . ' ៛' : '-'; ?></td>
                            <td><?= $payment ? date('d/m/Y', strtotime($payment['payment_date'])) : '-'; ?></td>
                            <td>
                                <?php
                                if ($payment) {
                                    switch ($payment['status']) {
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
                                <?php if (!$payment || $payment['status'] === 'Rejected'): ?>
                                    <a href="payment_form.php?student_id=<?= urlencode($userId); ?>&year=<?= $year; ?>"
                                        class="btn btn-sm btn-primary">បង់ប្រាក់ឥឡូវនេះ</a>
                                <?php elseif($payment && $payment['status'] === 'Approved'): ?>
                                    <?php
                                        // បន្ថែមប៊ូតុង "មើលបន្ថែម"
                                            echo ' <a href="payment_detail.php?payment_id=' . urlencode($payment['id']) . '" class="btn btn-sm btn-info ml-2">មើលបន្ថែម</a>';
                                        ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
</body>

</html>