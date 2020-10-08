<?php
$servername = "localhost";
$username = "";
$password = "";
$db = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$error = "";
// Check connection
if (!$conn) {
    die("Connection database failed: " . $conn->connect_error);
}
?>
