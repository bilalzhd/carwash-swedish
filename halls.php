<?php
session_start();
$page_title = "Hallar";
$title = "Hallar";
include 'partials/head.php';
include 'partials/db_connect.php'; ?>

<?php
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

$halls = mysqli_query($conn, "SELECT * FROM halls");
if (($_SERVER["REQUEST_METHOD"] == "POST") && (isset($_POST["edit_user"]))) {
    $num = $_POST['number_of_halls'];
    $date = $_POST['date'];
    $alreadyRecordedQuery = mysqli_query($conn, "SELECT * FROM records_2 WHERE date = '$date'");
    $id = mysqli_fetch_assoc($alreadyRecordedQuery);
    $id = isset($id['id']) ? $id['id'] : rand(0, 1);
    $alreadyRecordedEntries = mysqli_num_rows($alreadyRecordedQuery);
    // $alreadyRecordedEntriesTotal = 0;
    $entry = mysqli_fetch_assoc($alreadyRecordedQuery);
    // if ($alreadyRecordedEntries) {
    //     for ($i = 1; $i <= $entry['number_of_halls']; $i++) {
    //         $alreadyRecordedEntriesTotal += $entry['hall_' . $i];
    //     }
    // }
    if ($alreadyRecordedEntries > 0) {
        $error = true;
    } else {
        $query = mysqli_query($conn, "UPDATE halls SET count = '$num', date = '$date' WHERE date = '$date'");
        $update_query = mysqli_query($conn, "UPDATE records_2 SET number_of_halls = '$num' WHERE id = '$id'");
        if ($query) {
            $submitted = true;
            $halls = mysqli_query($conn, "SELECT * FROM halls");
        } else {
            $error = true;
        }
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
                <p class="mx-4">
                    <?php echo $error ? "Du har redan skrivit in några poster för idag, du kan inte byta hall nu, försök ta bort dessa poster." : "Kunden har lagts till!" ?>
                </p>
            </div>
        </div>
    </div>
<?php } ?>

<body class="bg-gray-100 pb-10">
    <div class="bg-white mt-10 container shadow-lg rounded-md p-5 md:p-10 max-w-[60rem] mx-auto">
        <div class="flex justify-between">
            <h1 class="text-3xl md:text-4xl text-center font-bold mb-4">Hallar</h1>
            <a href="/add-new-hall.php"><button class="flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">
                    Lägg till ny hallingång</button></a>
        </div>
        <div class="overflow-x-auto">
            <table id="example" class="display" style="width:100%">
                <thead class="text-white bg-indigo-500">
                    <tr>
                        <th>Antal salar</th>
                        <th>
                            Datum</th>
                        <th>Handlingar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($halls, 0);
                    while ($hall = mysqli_fetch_assoc($halls)) {
                        echo '<tr data-id="' . $hall['id'] . '">
                                <td class="w-2/5">' . $hall['count'] . '</td>
                                <td class="w-2/5">' . $hall['date'] . '</td>
                                <td class="w-1/5 flex whitespace-nowrap">
                                <div class="flex space-x-2">
                                <button id="' . $hall['id'] . '" data-id="' . $hall['id'] . '" class="edit-hall-button flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">
                                
Redigera
                                </button>
                                <button id="' . $hall['id'] . '" data-id="' . $hall['id'] . '" class="delete-hall-button flex items-center hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full text-white px-4 py-2 rounded-lg">
                                
Radera
                                </button>
                                <button data-id="' . $hall['id'] . '" class="submit-edit-button hidden flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg"> 
                                    
Skicka in
                                </button>
                                <button data-id="' . $hall['id'] . '" class="cancel-edit-button hidden flex hover:bg-indigo-700 transition-all duration-300 bg-indigo-500 w-full items-center text-white px-4 py-2 rounded-lg"> 
                                Annullera
                                </button>
                                    </div>
                                </td>
                            </tr>';
                    } ?>
                </tbody>

            </table>
        </div>
    </div>


</body>
<!-- edit popup -->


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script defer src="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"></script>
<script>
    let table = new DataTable('#example');
</script>
<script defer>
    const editButtons = document.querySelectorAll(".edit-hall-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            fetch('partials/getHalls.php?date=' + event.target.id)
                .then(response => response.json())
                .then(data => {
                    document.querySelector("#edit-popup").classList.remove("hidden");
                    const num = document.querySelector("#number_of_halls")
                    const dates = document.querySelectorAll(".date")
                    dates.forEach(function(d) {
                        d.value = data.date;
                    })
                    num.value = data.count;

                })
                .catch(error => console.error('Error:', error));
        });
    });

    document.getElementById("close-edit-popup").addEventListener("click", function() {
        document.querySelector("#edit-popup").classList.add("hidden")
    })

    const deleteButton = document.querySelectorAll(".delete-hall-button")
    deleteButton.forEach((button) => {
        button.addEventListener("click", async function(event) {
            if (confirm("Are you sure you want to delete?")) {
                const id = event.target.getAttribute("data-id");

                try {
                    const response = await fetch("partials/deleteHall.php", {
                        method: "POST",
                        headers: {
                            "Content-type": "application/x-www-form-urlencoded",
                        },
                        body: `id=${id}`
                    });

                    if (response.ok) {
                        const result = await response.json();
                        if (result.status == 1) {
                            const dataTable = $('#example').DataTable();
                            const rowIndex = dataTable.row(`[data-id="${id}"]`).index();
                            dataTable.row(rowIndex).remove().draw();
                        } else {
                            alert(result.msg)
                            console.error(result.msg);
                        }
                    } else {
                        // Handle HTTP error
                        console.error(`HTTP error: ${response.status}`);
                    }

                } catch (err) {

                }

            }
        });
    });
</script>
<script src="functions.js"></script>