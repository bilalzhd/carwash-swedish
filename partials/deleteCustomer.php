<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    $deleteOn = $_POST['delete_on'];
    $string_date = strtotime($deleteOn);

    // echo json_encode(["user" => $user_id, "delete_on" => $deleteOn]);
    // die();
    include_once("./db_connect.php");

    // Fetch customer name before deletion
    // $customer_query = mysqli_query($conn, "SELECT name FROM customers WHERE id = '$user_id'");
    // $customer_data = mysqli_fetch_assoc($customer_query);
    // $customer_name = $customer_data['name'];
    // $date = date("Y-m-d");
    // $update_records_query = mysqli_query($conn, "UPDATE records_2 SET customer_name = '$customer_name' WHERE customer_id = '$user_id'");

    // $delete_customer_query = mysqli_query($conn, "DELETE FROM customers WHERE id = '$user_id'");
    $delete_customer_query = mysqli_query($conn, "UPDATE customers SET delete_on = '$string_date', deleted = '1' WHERE id='$user_id'");
    
    // Update records with the customer name before deletion



    if ($delete_customer_query) {
        echo json_encode(["status" => 1, "msg" => "Customer has been deleted successfully"]);
    } else {
        echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => 0, "msg" => "There has been an error deleting the customer"]);
}
?>