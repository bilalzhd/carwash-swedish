<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST') {
<<<<<<< HEAD
       $user_id = $_POST['id'];
=======
    $user_id = $_POST['id'];
>>>>>>> ed74305ab451a2147a8c2986303f864b549dd31e
    include_once("./db_connect.php");
    $query = mysqli_query($conn, "DELETE FROM users WHERE id = '$user_id'");
    if($query) {
        echo json_encode(["success" => true, "msg" => "User deleted successfully"]);
<<<<<<< HEAD

    } else {

=======
    } else {
>>>>>>> ed74305ab451a2147a8c2986303f864b549dd31e
        echo json_encode(["success" => false, "msg" => "User not found"]);
    }

    mysqli_close($conn);
} else {
<<<<<<< HEAD
      echo json_encode(["success" => false, "msg" => "There was an error deleting the user!"]);
=======
    echo json_encode(["success" => false, "msg" => "There was an error deleting the user!"]);
>>>>>>> ed74305ab451a2147a8c2986303f864b549dd31e
}
?>