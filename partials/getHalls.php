<?php
include_once("./db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $date = $_GET['date'];
    $stmt = $conn->prepare("SELECT * FROM halls WHERE date = '$date'");
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo "User not found";
    }

    $stmt->close();
}

mysqli_close($conn);
?>