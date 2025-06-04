<?php
include '../conn_db.php'; 

if (isset($_POST['building_name'])) {
    $building_name = mysqli_real_escape_string($conn, $_POST['building_name']);
    
    // Fetch the number of rooms for the selected building from the database
    $sql = "SELECT room_number FROM addbuilding WHERE building_name = '$building_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $room_number = $row['room_number'];
        $rooms = range(1, $room_number);
    } else {
        $rooms = [];
    }
    
    echo json_encode($rooms);
}
?>
