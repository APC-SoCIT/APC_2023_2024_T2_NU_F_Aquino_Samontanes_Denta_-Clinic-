<?php
session_start();
include '../db_conn.php';

function updateAppointmentStatus($conn, $appointmentId, $status) {
    $updateSql = "UPDATE tbl_request SET appointment_condition = '$status' WHERE id = $appointmentId";
    $updateResult = mysqli_query($conn, $updateSql);
    if (!$updateResult) {
        die("Error updating appointment status: " . mysqli_error($conn));
    }
}

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Assuming 'accept' button is clicked
        if (isset($_POST['accept'])) {
            $appointmentId = $_POST['accept'];

            // Retrieve appointment details from tbl_request
            $sqlSelect = "SELECT * FROM tbl_request WHERE id = $appointmentId";
            $resultSelect = mysqli_query($conn, $sqlSelect);

            if ($row = mysqli_fetch_assoc($resultSelect)) {
                // Insert the appointment details into tbl_patients
                $patientId = $row['patient_id'];
                $firstName = $row['first_name'];
                $middleName = $row['middle_name'];
                $lastName = $row['last_name'];
                $gender = $row['gender'];
                $cn = $row['Contact_Number'];

                $sqlInsert = "INSERT INTO tbl_patients (patient_id, first_name, middle_name, last_name, gender, 
                contact_number) 
                            VALUES ('$patientId', '$firstName', '$middleName', '$lastName', '$gender', '$cn')";
                $resultInsert = mysqli_query($conn, $sqlInsert);

                // Update appointment status to 'accepted'
                updateAppointmentStatus($conn, $appointmentId, 'accepted');
            }
        } elseif (isset($_POST['disapprove'])) {
            $appointmentId = $_POST['disapprove'];
            updateAppointmentStatus($conn, $appointmentId, 'disapproved');
        }
    }

    $sql = "SELECT id, patient_id, first_name, middle_name, last_name, date_of_appointment, gender, appointment_condition
    FROM tbl_request 
    WHERE appointment_condition='pending'
    ORDER BY date_of_appointment ASC";

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
        <link rel="stylesheet" type="text/css" href="../css's/index.css">
    </head>
    <body>
        <header>
            <nav>
                <div class="logo">
                    <a href="#" aria-label="Homepage">
                        <img src="../pics/Logo.png" alt="" class="src">
                    </a>
                </div>
                <ul>
                    <li><a href="">Check Appointments</a></li>
                    <li><a href="">Appointment Calendar</a></li>
                    <li><a href="pTable.php">Patient Records Table</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><a href="request.php" class="btn-nav">Schedule Appointment</a></li>
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
        <h2>Disapproved Appointments</h2>
        <table border="1">
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
                    echo "<button type='submit' name='accept' value='{$row['id']}'>Accept</button>";
                    echo "<button type='submit' name='disapprove' value='{$row['id']}'>Disapprove</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
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
    header("Location: ../index.php");
    exit();
}
?>
