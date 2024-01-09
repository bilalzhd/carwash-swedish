<?php
$server = "localhost";
$username = "root";  // root
$password = "";  
$database = "car-wash";  // car-wash
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: ".mysqli_connect_errno());
  }
?>
