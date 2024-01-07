<?php
$server = "localhost";
$username = "alvisstad_tavla";  // root
$password = "tavla";  // 
$database = "alvisstad_tavla"; // car-wash
$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn) {
    die("Connection failed: ".mysqli_connect_errno());
  }
?>
