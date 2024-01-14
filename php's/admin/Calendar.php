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
            $sqlSelect = "SELECT * FROM appointments WHERE id = $appointmentId";
            $resultSelect = mysqli_query($conn, $sqlSelect);

            if ($row = mysqli_fetch_assoc($resultSelect)) {
                // Insert the appointment details into patients
                $patientId = $row['patient_id'];
                $firstName = $row['first_name'];
                $middleName = $row['middle_name'];
                $lastName = $row['last_name'];
                $gender = $row['gender'];
                $email = $row['email_address'];
                $cn = $row['Contact_Number'];
                
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

    $sql = "SELECT id, patient_id, first_name, middle_name, last_name, date_of_appointment, gender, appointment_condition
    FROM appointments 
    WHERE appointment_condition='approved'
    ORDER BY date_of_appointment ASC";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $sql_done = "SELECT id, patient_id, first_name, middle_name, last_name, date_of_appointment, gender, appointment_condition
    FROM appointments 
    WHERE appointment_condition='Done'
    ORDER BY date_of_appointment ASC";

    $result_done = mysqli_query($conn, $sql_done);
    if (!$result_done) {
        die("Error: " . mysqli_error($conn));
    }
    ?>

    
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin.css">
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

        <div>
    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>
    
    <div class="table_appointments">
    <h2 class="title">Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Date of Appointment</th>
                    <th>Gender</th>
                    <th>Appointment Condition</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
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
                    echo "<form method='post'>";
                    echo "<button type='submit' name='Done' value='{$row['id']}'>Done</button>";
                    echo "</form>";
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

    <?php
    if (mysqli_num_rows($result_done) > 0) {
    ?>
    
    <div class="table_appointments">
    <h2 class="title">Finished Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Date of Appointment</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result_done)) {
                    echo "<tr>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['middle_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['date_of_appointment']}</td>";
                    echo "<td>{$row['gender']}</td>";
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


    </body>
    </html>

    <?php
} else {
    header("Location: ../../index.php");
    exit();
}
?>
