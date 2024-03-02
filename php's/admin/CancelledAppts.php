<?php
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $sql_cancel = "SELECT DISTINCT appointments.*, patients.first_name, patients.middle_name, patients.last_name, patients.gender
    FROM appointments 
    INNER JOIN patients ON appointments.patient_id = patients.patient_id
    WHERE appointments.appointment_condition='cancelled'
    ORDER BY appointments.date_of_appointment ASC";

    $result_cancel = mysqli_query($conn, $sql_cancel);
    if (!$result_cancel) {
        die("Error: " . mysqli_error($conn));
    }
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/Calendar.css">

        <script>
            // JavaScript functions to open and close the modal
            function openModal(appointmentId) {
                document.getElementById("overlay").style.display = "block";
                document.getElementById('cancelModal').style.display = 'block';
                // Set the appointment ID in a hidden input field within the modal form
                document.getElementById('appointmentIdInput').value = appointmentId;
            }


            function closeModal() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("cancelModal").style.display = "none";
            }
            
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

                xhttp.open("GET", `sort_appt.php?column=${column}&order=${sortOrders[column]}`, true);
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
                    <li class="dropdown">
                        <a href="CheckAppointments.php" class="dropbtn sel_page">Appointments</a>
                        <div class="dropdown-content">
                            <a href="CheckAppointments.php">Check Appointments</a>
                            <a href="Calendar.php" class="sel_page">Appointment Calendar</a>
                            <a href="FinishedAppts.php">Finished Appointments</a>
                            <a href="CancelledAppts.php">Cancelled Appointments</a>
                        </div>
                    </li>
                    <li><a href="pTable.php">Patient Records Table</a></li>
                    <li><a href="ArchivedRecords.php">Archived Records</a></li>
                    <li><a href="../auth/logout.php">Logout</a></li>
                </ul>
                <div class="hamburger">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </nav>
        </header>

        <div>

    <?php
        if (mysqli_num_rows($result_cancel) > 0) {
        ?>
        
        <div class="table_appointments">
        <div class="table_search">
            <h2 class="title">Cancelled Appointments</h2>
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names...">
        </div>
            <table>
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Date of Appointment</th>
                        <th>Gender</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php
                    while ($row = mysqli_fetch_assoc($result_cancel)) {
                        echo "<tr>";
                        echo "<td>{$row['patient_id']}</td>";
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['middle_name']}</td>";
                        echo "<td>{$row['last_name']}</td>";
                        echo "<td>{$row['date_of_appointment']}</td>";
                        echo "<td>{$row['gender']}</td>";
                        echo "<td>{$row['reason_of_cancel']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>    
        <?php
        } else {
            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>There are no pending appointments.</p>";
        }
        ?>
    </div>

    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("table-body");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                tdFirstName = tr[i].getElementsByTagName("td")[1];
                tdMiddleName = tr[i].getElementsByTagName("td")[2];
                tdLastName = tr[i].getElementsByTagName("td")[3];
                tdDate = tr[i].getElementsByTagName("td")[4];
                if (tdFirstName || tdMiddleName || tdLastName || tdDate) {
                    txtValueFirstName = tdFirstName.textContent || tdFirstName.innerText;
                    txtValueMiddleName = tdMiddleName.textContent || tdMiddleName.innerText;
                    txtValueLastName = tdLastName.textContent || tdLastName.innerText;
                    txtValueDate = tdDate.textContent || tdDate.innerText;
                    if (txtValueFirstName.toUpperCase().indexOf(filter) > -1 ||
                        txtValueMiddleName.toUpperCase().indexOf(filter) > -1 ||
                        txtValueLastName.toUpperCase().indexOf(filter) > -1 ||
                        txtValueDate.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <script src="../../js's/scriptindex.js"></script>
    <script src="https://kit.fontawesome.com/595a890311.js" crossorigin="anonymous"></script>
    </body>
    </html>

    <?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>
