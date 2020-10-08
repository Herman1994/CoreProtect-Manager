<?php
$servername = "49.12.47.27";
$username = "u399_dudhvBDMlk";
$password = "fvdsfMOnLW0ALjr0Y1wGztJ8";
$db = "s399_coreprotect";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$error = "";
// Check connection
if (!$conn) {
    die("Connection database failed: " . $conn->connect_error);
}
?>