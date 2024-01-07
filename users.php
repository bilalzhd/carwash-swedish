<?php
session_start();
$page_title = "Användare";
$title = "Användare";
include 'partials/head.php';
include 'partials/db_connect.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$role = $_SESSION['role'];

if ($role != 0) {
    header("Location: dashboard.php");
    exit();
}
?>

<?php
$submitted = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $checkQuery = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($checkQuery) > 0) {
        $error = true;
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$password' , '$role')");
        if ($query) {
            $submitted = true;
        } else {
            $error = true;
        }
    }
}

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");

// TO EDIT USER

if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["edit_user"]))) {
    $username = $_POST['edit_username'];
    $role = $_POST['edit_role'];
    $password = $_POST['edit_password'];
    $id = $_POST['user_id'];


    $query = mysqli_query($conn, "UPDATE users SET username = '$username', role = '$role', password = '$password' WHERE id = '$id'");
    if ($query) {
        $submitted = true;
        $users = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
    } else {
        $error = true;
    }
}
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_processing,
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 10px !important;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid indigo !important;
    }
</style>

<?php include("./partials/header.php") ?>

<?php if ($submitted || $error) { ?>
    <div class="<?php echo $submitted ? 'bg-green-500' : 'bg-red-500' ?> alert rounded-b text-white px-4 py-3 shadow-md text-xl" role="alert">
        <div class="flex items-center">
            <div class="py-1 rounded-full border-2 p-1 border-white">
                <?php echo $submitted ? ('<svg height="18px" version="1.1" viewBox="0 0 18 15" width="18px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="#fff" id="Core" transform="translate(-423.000000, -47.000000)"><g id="check" transform="translate(423.000000, 47.500000)"><path d="M6,10.2 L1.8,6 L0.4,7.4 L6,13 L18,1 L16.6,-0.4 L6,10.2 Z" id="Shape"/></g></g></g></svg>') : ('<svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 384 512"><path fill="#fff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>')
                ?>
            </div>
            <div>
                <p class="mx-4"><?php echo $error ? "Användarnamn finns redan!" : "Användaren har lagts till!" ?></p>
            </div>
        </div>
    </div>
<?php } ?>

<body class="bg-gray-100">
    <div class="pt-6 pb-10 space-y-12">
        <div class="pb-12">
            <form method="POST" action="?" class="overflow-y-auto">
                <div class="max-w-5xl mx-auto border-gray-900/10 pb-12">
                    <div class="mt-10 px-5 md:px-0 md:flex justify-center items-center gap-x-6 gap-y-4">
                        <div class="mb-4 md:mb-0">
                            <label for="name" class="block text-xl font-medium leading-6 text-gray-900">Användarnamn</label>
                            <div class="mt-2">
                                <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="username" id="name" placeholder="Skriv in ditt användarnamn" required>

                            </div>
                        </div>
                        <div class="mb-4 md:mb-0">
                            <label for="hall" class="block text-xl font-medium leading-6 text-gray-900">Role</label>
                            <div class="mt-2">
                                <select id="role" name="role" class="min-w-[200px] px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                    <option value="" disabled selected>Välj roll</option>
                                    <option value="0">
Administration</option>
                                    <option value="1">
Användare</option>
                                    <option value="2">Chef</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4 md:mb-0">
                            <label for="phone" class="block text-xl font-medium leading-6 text-gray-900">Lösenord</label>
                            <div class="mt-2">
                                <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="password" id="phone" required>
                            </div>
                        </div>
                        <div class="sm:col-span-12 flex items-end">
                            <button name="add_user" class="mt-[27.5px] hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">Lägg till användare</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="bg-white container shadow-lg rounded-md p-5 md:p-10 max-w-[60rem] mx-auto">
                <h1 class="text-4xl text-center font-bold mb-4">
Användarlista</h1>
                <div class="overflow-x-auto">
                    <table id="example" class="display" style="width:100%">
                        <thead class="text-white bg-indigo-500">
                            <tr>
                                <th>Användarnamn</th>
                                <th>
Roll</th>
                                <th>
Lösenord</th>
                                <th>Åtgärder</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($user = mysqli_fetch_assoc($users)) {
                                $user_role = $user['role'] == 0 ? 'Admin' : ($user['role'] == 1 ? 'Shopkeeper' : 'Manager');
                            ?>
                                <tr id="<?php echo $user['id'] ?>" data-id="<?php echo $user['id'] ?>">
                                <td class="w-1/4">
                                <span class="editSpan name"><?php echo $user['username'] ?></span>
                                <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_name" id="edit_name" placeholder="Enter name" required style="display: none" value="<?php echo $user['username'] ?>">
                                </td>
                                <td class="w-1/4">
                                <span class="editSpan role"><?php echo $user_role ?></span>
                                <select style="display: none" id="edit_role" name="edit_role" class="editInput min-w-[200px] px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                        <option value="" disabled>Välj roll</option>
                                        <option value="0" <?php echo $user['role'] == 0 ? 'selected' : "" ?>>Administration</option>
                                        <option value="1" <?php echo $user['role'] == 1 ? 'selected' : "" ?>>
Användare</option>
                                        <option value="2" s<?php echo $user['role'] == 2 ? 'selected' : "" ?>>
Chef</option>
                                    </select>
                                </td>
                                <td class="w-1/4">
                                <span class="editSpan password"><?php echo $user['password'] ?></span>
                                <input class="editInput px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_password" id="edit_password" style="display: none" required value="<?php echo $user['password'] ?>">
                                </td>
                                <td class="w-1/4 flex whitespace-nowrap">
                                    <div class="flex space-x-2">
                                    <button data-id="' . $user['id'] . '" class="editBtn flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">
Redigera</button>
                                    <button data-id="' . $user['id'] . '" class="deleteBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg">
Radera</button>
                                    <button data-id="' . $user['id'] . '" class="saveBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">
Spara</button>
                                    <button data-id="' . $user['id'] . '" class="confirmBtn flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">Bekräfta</button>
                                    <button data-id="' . $user['id'] . '" class="cancelBtn flex hover:bg-indigo-700 transition-all duration-300 bg-gray-500 w-full items-center text-white px-4 py-2 rounded-lg" style="display: none;">
Annullera</button>
                                    </div>
                                </td>
                            </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</body>
<!-- delete popup -->

<div class="hidden min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover" id="delete-popup">
    <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
    <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">
        <div class="">
            <div class="text-center p-5 flex-auto justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 -m-1 flex items-center text-indigo-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 flex items-center text-indigo-500 mx-auto" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <h2 class="text-xl font-bold py-4 ">
Är du säker?</h3>
                    <p class="text-sm text-gray-500 px-8">Vill du verkligen ta bort användaren?
                        Denna process kan inte ångras</p>
            </div>
            <!--footer-->
            <div class="p-3  mt-2 text-center space-x-4 md:block">
                <button id="" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                    
Annullera
                </button>
                <button name="delete_user" type="submit" class="mb-2 md:mb-0 bg-indigo-500 border-indigo-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-indigo-700">
Spara ändringar</button>
            </div>
        </div>
    </div>
</div>
<!-- edit popup -->

<div class="hidden min-w-screen h-screen animated fadeIn faster  fixed  left-0 top-0 flex justify-center items-center inset-0 z-50 outline-none focus:outline-none bg-no-repeat bg-center bg-cover" id="edit-popup">
    <div class="absolute bg-black opacity-80 inset-0 z-0"></div>
    <div class="w-full  max-w-lg p-5 relative mx-auto my-auto rounded-xl shadow-lg  bg-white ">
        <div class="">
            <div class="p-5 flex-auto">
                <div class="text-indigo-500 flex w-full flex-col items-center space-y-4">
                    <i class="fa fa-pencil text-4xl"></i>
                    <h3 class="text-4xl font-bold text-center">
Redigera användare</h3>
                </div>
                <form method="POST" action="?" class="overflow-y-auto">
                    <input type="hidden" id="user_id" name="user_id">
                    <div class="max-w-5xl mx-auto border-gray-900/10 pb-12">
                        <div class="mt-10 gap-x-6 space-y-6">
                            <div>
                                <label for="name" class="block text-xl font-medium leading-6 text-gray-900">
Användarnamn</label>
                                <div class="mt-2">
                                    <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_username" id="edit_username" placeholder="Enter Username" required>

                                </div>
                            </div>
                            <div>
                                <label for="hall" class="block text-xl font-medium leading-6 text-gray-900">Role</label>
                                <div class="mt-2">
                                    <select id="edit_role" name="edit_role" class="min-w-[200px] px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>
Välj roll</option>
                                        <option value="0">Admin</option>
                                        <option value="1">Shopkeeper</option>

                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="phone" class="block text-xl font-medium leading-6 text-gray-900">Password</label>
                                <div class="mt-2">
                                    <input class="px-4 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" type="text" name="edit_password" id="edit_password" required>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
            <!--footer-->
            <div class="p-3  mt-2 text-center space-x-4 md:block">
                <button id="close-edit-popup" type="button" class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-full hover:shadow-lg hover:bg-gray-100">
                    Cancel
                </button>
                <button type="submit" name="edit_user" class="mb-2 md:mb-0 bg-indigo-500 border-indigo-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-full hover:shadow-lg hover:bg-indigo-700">Save Changes</button>
            </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script defer src="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"></script>



<script>
    $(document).ready(function() {
        var dataTable = $('#example').DataTable();
        if ($('.alert')) {
            $('.alert').fadeOut(5000)
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('.editBtn').on('click', function() {
            //hide edit span
            $(this).closest("tr").find(".editSpan").hide();

            //show edit input
            $(this).closest("tr").find(".editInput").show();

            //hide edit button
            $(this).closest("tr").find(".editBtn").hide();

            //hide delete button
            $(this).closest("tr").find(".deleteBtn").hide();

            //show save button
            $(this).closest("tr").find(".saveBtn").show();

            //show cancel button
            $(this).closest("tr").find(".cancelBtn").show();

        });

        $('.saveBtn').on('click', function() {
            $('#userData').css('opacity', '.5');

            var trObj = $(this).closest("tr");
            var ID = $(this).closest("tr").attr('id');
            var inputData = $(this).closest("tr").find(".editInput").serialize();
            $.ajax({
                type: 'POST',
                url: 'partials/editUsers.php',
                dataType: "json",
                data: 'action=edit&id=' + ID + '&' + inputData,
                success: function(response) {
                    if (response.status == 1) {
                        let role = response.data.edit_role == 0 ? 'Admin' : response.data.edit_role == 1 ? 'Shopkeeper' : 'Manager';
                        trObj.find(".editSpan.name").text(response.data.edit_name);
                        trObj.find(".editSpan.role").text(role);
                        trObj.find(".editSpan.password").text(response.data.edit_password);
                         
                        trObj.find(".editInput.name").val(response.data.edit_name);
                        trObj.find(".editInput.role").val(response.data.edit_role);
                        trObj.find(".editInput.password").val(response.data.password);

                        trObj.find(".editInput").hide();
                        trObj.find(".editSpan").show();
                        trObj.find(".saveBtn").hide();
                        trObj.find(".cancelBtn").hide();
                        trObj.find(".editBtn").show();
                        trObj.find(".deleteBtn").show();
                    } else {
                        alert(response.msg);
                    }
                    $('#userData').css('opacity', '');
                }
            });
        });

        $('.cancelBtn').on('click', function() {
            //hide & show buttons
            $(this).closest("tr").find(".saveBtn").hide();
            $(this).closest("tr").find(".cancelBtn").hide();
            $(this).closest("tr").find(".confirmBtn").hide();
            $(this).closest("tr").find(".editBtn").show();
            $(this).closest("tr").find(".deleteBtn").show();

            //hide input and show values
            $(this).closest("tr").find(".editInput").hide();
            $(this).closest("tr").find(".editSpan").show();
        });

        $('.deleteBtn').on('click', function() {
            //hide edit & delete button
            $(this).closest("tr").find(".editBtn").hide();
            $(this).closest("tr").find(".deleteBtn").hide();

            //show confirm & cancel button
            $(this).closest("tr").find(".confirmBtn").show();
            $(this).closest("tr").find(".cancelBtn").show();
        });

        $('.confirmBtn').on('click', function() {
            $('#userData').css('opacity', '.5');

            var trObj = $(this).closest("tr");
            var ID = $(this).closest("tr").attr('id');
            $.ajax({
                type: 'POST',
                url: 'partials/deleteUser.php',
                dataType: "json",
                data: 'action=delete&id=' + ID,
                success: function(response) {
                    if (response.status == 1) {
                        trObj.remove();
                    } else {
                        trObj.find(".confirmBtn").hide();
                        trObj.find(".cancelBtn").hide();
                        trObj.find(".editBtn").show();
                        trObj.find(".deleteBtn").show();
                        // alert(response.msg);
                        window.location.reload();
                    }
                    $('#userData').css('opacity', '');
                }
            });
        });
    });
</script>

<script src="functions.js"></script>