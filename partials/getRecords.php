<?php
// Assuming you have a MySQL connection established
include_once("./db_connect.php");
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["date"])) {
    $date = $_GET["date"];
    $string_date = strtotime($date);
    $query = "SELECT  c.id AS customer_id, c.name AS customer_name, c.delete_on, r.hall_1, r.hall_2, r.hall_3, r.hall_4, r.hall_5, r.hall_6, r.hall_7, r.hall_8, r.hall_9, r.hall_10, r.number_of_halls, r.date FROM customers c LEFT JOIN records_2 r ON c.id = r.customer_id AND r.date = '$date' WHERE c.timestamp <= '$string_date' AND (c.delete_on = 0 OR c.delete_on >= '$string_date');";

    $result = mysqli_query($conn, $query);
    $totalRecords = 0;
    $total = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            for ($i = 1; $i <= $row['number_of_halls']; $i++) {
                $total += $row['hall_' . $i];
            }
            $totalRecords = $total;
        };

        // Return the result as JSON
        header('Content-Type: application/json');
        echo json_encode(["totalRecords" => $totalRecords]);
    } else {
        // Handle query failure
        header('Content-Type: application/json');
        echo json_encode(["error" => "Query failed"]);
    }
} else {
    // Handle invalid or missing date parameter
    header('Content-Type: application/json');
    echo json_encode(["error" => "Invalid or missing date parameter"]);
}
