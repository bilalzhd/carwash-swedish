<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['id'];
    include_once("./db_connect.php");

    $delete_customer_query = mysqli_query($conn, "UPDATE customers SET delete_on = 0, deleted = '0' WHERE id='$user_id'");
    

    if ($delete_customer_query) {
        echo json_encode(["status" => 1, "msg" => "Customer has been deleted successfully"]);
    } else {
        echo json_encode(["status" => 0, "msg" => "There has been an error undoing deleting"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => 0, "msg" => "There has been an error undoing deleting"]);
}
?>