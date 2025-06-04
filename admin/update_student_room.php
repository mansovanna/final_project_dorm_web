<?php
session_start();
include '../conn_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $new_building = mysqli_real_escape_string($conn, $_POST['building_name']);
    $new_room = mysqli_real_escape_string($conn, $_POST['room_number']);

    // Get current student data
    $sql_current = "SELECT building, room, lastname, name, skill, year, education_level, phone_student FROM register WHERE student_id = ?";
    $stmt_current = $conn->prepare($sql_current);
    $stmt_current->bind_param("s", $student_id);
    $stmt_current->execute();
    $result_current = $stmt_current->get_result();

    if ($result_current->num_rows > 0) {
        $current_data = $result_current->fetch_assoc();

        // Save old data to history table
        $sql_history = "INSERT INTO history (student_id, building, room, lastname, name, skill, year, education_level, phone_student) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_history = $conn->prepare($sql_history);
        $stmt_history->bind_param("ssssssiss", $student_id, $current_data['building'], $current_data['room'], $current_data['lastname'], $current_data['name'], $current_data['skill'], $current_data['year'], $current_data['education_level'], $current_data['phone_student']);
        $stmt_history->execute();
        $stmt_history->close();
    }

    $stmt_current->close();

    // Update student's building and room in the database
    $sql_update = "UPDATE register SET building = ?, room = ? WHERE student_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sis", $new_building, $new_room, $student_id);

    if ($stmt_update->execute()) {
        // Redirect to the student list page after successful update
        header("Location: build_number.php?building_name=" . urlencode($new_building) . "&room=" . urlencode($new_room));
        exit();
    } else {
        echo "Error: " . $stmt_update->error;
    }

    $stmt_update->close();
}

$conn->close();
?>
