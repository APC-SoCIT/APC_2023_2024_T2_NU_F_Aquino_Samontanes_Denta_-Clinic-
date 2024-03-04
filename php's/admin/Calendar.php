<?php
session_start();
include '../../db_conn.php';

function updateAppointmentStatus($conn, $appointmentId, $status) {
    $updateSql = "UPDATE appointments SET appointment_condition = '$status' WHERE id = $appointmentId";
    $updateResult = mysqli_query($conn, $updateSql);
    if (!$updateResult) {
        die("Error updating appointment status: " . mysqli_error($conn));
    }
}

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assuming 'approve' button is clicked
        if (isset($_POST['Done'])) {
            $appointmentId = $_POST['Done'];

            // Retrieve appointment details from appointments
            $sqlSelect = "SELECT DISTINCT appointments.*, patients.*
            FROM appointments 
            INNER JOIN patients ON appointments.patient_id = patients.patient_id
            WHERE appointments.id = $appointmentId";
            $resultSelect = mysqli_query($conn, $sqlSelect);

            if ($row = mysqli_fetch_assoc($resultSelect)) {
                // Insert the appointment details into patients
                $patientId = $row['patient_id'];
                $firstName = $row['first_name'];
                $middleName = $row['middle_name'];
                $lastName = $row['last_name'];
                $gender = $row['gender'];
                $email = $row['email_address'];
                $cn = $row['contact_number'];
                
                date_default_timezone_set('Asia/Manila');
                $currentDateTime = date('Y-m-d H:i:s');
                $archived = 'no';

                $sqlCheckPatient = "SELECT * FROM patients WHERE patient_id = '$patientId'";
                $resultCheckPatient = mysqli_query($conn, $sqlCheckPatient);

                if (mysqli_num_rows($resultCheckPatient) == 0) {
                    // Patient does not exist, so we can insert
                    $sqlInsert = "INSERT INTO patients (patient_id, first_name, middle_name, last_name, gender, email_address, contact_number, created_at, is_archived) 
                                VALUES ('$patientId', '$firstName', '$middleName', '$lastName', '$gender', '$email', '$cn', '$currentDateTime', '$archived')";

                    $resultInsert = mysqli_query($conn, $sqlInsert);

                    if (!$resultInsert) {
                        die("Error inserting patient record: " . mysqli_error($conn));
                    }
                }

                // Update appointment status to 'approved'
                updateAppointmentStatus($conn, $appointmentId, 'Done');
            }
        } 
    }

    if(isset($_POST["cancel_apnmt"])) {
        $AppointmentID = $_POST['appointment_id'];
        $Reason = $_POST['reason_of_cancel'];
        $Cancel = 'cancelled';
    
        if (empty($Reason)) {
            header("Location: Calendar.php?error=Write a reason for cancellation");
            exit();
        } else {
            $csql = "UPDATE appointments SET reason_of_cancel = '$Reason', appointment_condition = '$Cancel' WHERE id = '$AppointmentID'";
            // Execute the SQL query
            $cresult = mysqli_query($conn, $csql);
            // Check if the query was successful
            if ($cresult) {
                header("Location: Calendar.php?success=Appointment cancelled");
                exit();
            } else {
                // Handle the error
            }
        }
    }

    $sql = "SELECT DISTINCT appointments.*, patients.first_name, patients.middle_name, patients.last_name, patients.gender
    FROM appointments 
    INNER JOIN patients ON appointments.patient_id = patients.patient_id
    WHERE appointments.appointment_condition='approved'
    ORDER BY appointments.date_of_appointment ASC";

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
        <link rel="stylesheet" type="text/css" href="../../css's/admin/Calendar.css">
        <link rel="icon" href="../../pics/Logo.png" type="image/png">

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

                xhttp.open("GET", `sort/sort_calendar.php?column=${column}&order=${sortOrders[column]}`, true);
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
                        <a href="CheckAppointments.php" class="dropbtn sel_page" >Appointments</a>
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
    if (mysqli_num_rows($result) > 0) {
    ?>
    
    <div class="table_appointments">
        <div class="table_search">
            <h2 class="title">Appointments</h2>
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names...">
        </div>
        <table>
            <thead>
                <tr>
                    <th onclick="sortTable('patient_id')" class="sort_th">Patient ID</th>
                    <th onclick="sortTable('first_name')" class="sort_th">First Name</th>
                    <th onclick="sortTable('middle_name')" class="sort_th">Middle Name</th>
                    <th onclick="sortTable('last_name')" class="sort_th">Last Name</th>
                    <th onclick="sortTable('date_of_appointment')" class="sort_th">Date of Appointment</th>
                    <th onclick="sortTable('gender')" class="sort_th">Gender</th>
                    <th>Appointment Condition</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['middle_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['date_of_appointment']}</td>";
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['appointment_condition']}</td>";
                    echo "<td>";
                    echo "<div style=\"text-align: center; display: flex; flex-align: horizontal;\">";
                    echo "<form method='post'>";
                    echo "<button class=\"btn-nav\" type='submit' name='Done' value='{$row['id']}'>Done</button>";
                    echo "</form>";
                    echo "<button class=\"btn-nav cancel\" style=\"\" onclick=\"openModal('{$row['id']}')\">Cancel</button>";
                    echo "</div>";
                    echo "</td>";
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

    <!-- Modal -->
    <div class="overlay" id="overlay" onclick="closeModal()"></div> <!-- Overlay -->
    <div class="modal" id="cancelModal">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Are you sure you want to cancel the appointment?</h2>
        <form action="" method="post">
            <!-- Hidden input field to store the appointment ID -->
            <input type="hidden" id="appointmentIdInput" name="appointment_id">
            <div class="form-group">
                <label for="reason_of_cancel">Reason for cancelling:</label>
                <div class="input-wrapper">
                    <textarea name="reason_of_cancel" id="reason_of_cancel" rows="1" cols="50"></textarea>
                </div>
            </div>
            <input type="submit" value="Cancel appointment" name="cancel_apnmt" class="cancel_bttn">
        </form>
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
