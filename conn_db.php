<?php

// Establish connection to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dorm_ksit";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
