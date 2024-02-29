<?php
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $sql = "SELECT DISTINCT patient_id, first_name, middle_name, last_name, gender, contact_number
            FROM patients 
            WHERE is_archived != 'yes'";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/admin.css">

        <!-- Add the following script inside the <head> section of your HTML -->
        <script>
            let sortOrders = {};

            function sortTable(column) {
                if (!sortOrders[column]) {
                    sortOrders[column] = 'asc';
                } else {
                    sortOrders[column] = (sortOrders[column] === 'asc') ? 'desc' : 'asc';
                }

                console.log(`Sorting by column ${column} in ${sortOrders[column]} order`);

                const xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("table-body").innerHTML = this.responseText;
                    }
                };

                xhttp.open("GET", `sort.php?column=${column}&order=${sortOrders[column]}`, true);
                xhttp.send();
            }
        </script>

    </head>
    <body>
        <header>
            <nav>
                <div class="logo">
                    <a href="#" aria-label="Homepage">
                        <img src="../../pics/Logo.png" alt="" class="src">
                    </a>
                </div>
                <ul>
                    <li><a href="CheckAppointments.php">Check Appointments</a></li>
                    <li><a href="Calendar.php">Appointment Calendar</a></li>
                    <li><a href="pTable.php">Patient Records Table</a></li>
                    <li><a href="ArchivedRecords.php">Archived Records</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                    <!--<li><a href="request.php" class="btn-nav">Schedule Appointment</a></li>-->
                </ul>
                <div class="hamburger">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </nav>
        </header>

        <?php if (isset($_GET['success'])) { ?>
            <p class="success" id="successMessage"><?php echo $_GET['success']; ?></p>
            <script>
                setTimeout(function() {
                    document.getElementById('successMessage').classList.add('hide');
                }, 1000);
            </script>
        <?php } ?>

        <?php if (isset($_GET['error'])) { ?>
            <p class="error" id="errorMessage"><?php echo $_GET['error']; ?></p>
            <script>
                setTimeout(function() {
                    document.getElementById('errorMessage').classList.add('hide');
                }, 1000);
            </script>
        <?php } ?>
    
        <div>
    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>
    
    <div class="table_appointments">
    <div class="table_add">
        <h2 class="title">Patient Records</h2>
        <a href="pCreate.php" class="no-underline">
            <button id="plusbtn">&#43;</button>
        </a>
    </div>
        <table border="1">
            <!-- Update the <thead> section of your HTML -->
            <thead>
                <tr>
                    <th onclick="sortTable('patient_id')" class="sort_th">Patient ID</th>
                    <th onclick="sortTable('first_name')" class="sort_th">First Name</th>
                    <th onclick="sortTable('middle_name')" class="sort_th">Middle Name</th>
                    <th onclick="sortTable('last_name')" class="sort_th">Last Name</th>
                    <th onclick="sortTable('gender')" class="sort_th">Gender</th>
                    <th onclick="sortTable('contact_number')" class="sort_th">Contact Number</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody id="table-body">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div id="myModal" class="modal">';
                    echo '<h2>Confirmation</h2>';
                    echo '<p>Are you sure you want to archive the record?</p>';
                    echo '<button onclick="cancelAction();">Cancel</button>';
                    echo '<a href="pArchive.php?patient_id=' . $row['patient_id'] . '">Proceed</a>';
                    echo '</div>';
                    echo "<tr>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['middle_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['contact_number']}</td>";
                    echo "<td style='margin: auto;'><a href='pView.php?patient_id=" . $row['patient_id'] . "'><button class=\"btn-nav\">View</button></a>
                    <a href='pUpdate.php?patient_id=" . $row['patient_id'] . "'><button class=\"btn-nav\">Update</button></a>
                    <a href='#' class='custom-link' onclick='openModal();'><button class=\"btn-nav cancel\">Archive</button></a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    } else {
        echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>There are no patients in the table.</p>";
    }
    ?>
</div>

    <script>
        let userAction;

        function openModal() {
            userAction = undefined;
            document.getElementById('myModal').style.display = 'block';
        }

        function cancelAction() {
            userAction = 'cancel';
            closeModal();
        }

        function proceed() {
            userAction = 'proceed';
            closeModal();
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }
    </script>

    </body>
    </html>

    <?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>
