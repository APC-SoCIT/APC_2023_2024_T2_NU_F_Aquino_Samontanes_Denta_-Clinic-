<?php
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $PatientID = $_SESSION['id'];
    $sql_pending = "SELECT *
        FROM appointments 
        WHERE patient_id = $PatientID 
        AND appointment_condition = 'pending'
        ORDER BY date_of_appointment";

    $sql = "SELECT *
        FROM appointments 
        WHERE patient_id = $PatientID 
        AND appointment_condition = 'approved' 
        ORDER BY date_of_appointment";

    $sql_done = "SELECT *
        FROM appointments 
        WHERE patient_id = $PatientID 
        AND appointment_condition = 'Done'
        GROUP BY appointment_condition
        ORDER BY date_of_appointment";

    $sql_cancel = "SELECT *
        FROM appointments 
        WHERE patient_id = $PatientID 
        AND (appointment_condition = 'cancelled' OR appointment_condition = 'disapproved')
        ORDER BY date_of_appointment";


    $result_pending = mysqli_query($conn, $sql_pending);
    if (!$result_pending) {
        die("Error: " . mysqli_error($conn));
    }

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $result_done = mysqli_query($conn, $sql_done);
    if (!$result_done) {
        die("Error: " . mysqli_error($conn));
    }

    $result_cancel = mysqli_query($conn, $sql_cancel);
    if (!$result_cancel) {
        die("Error: " . mysqli_error($conn));
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aquino Samontanes Dental Clinic</title>
    <!-- Load Bootstrap CSS first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css's/patient/Calendar.css">

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

        // JavaScript functions to open and close the modal
        function openModal2(appointmentId) {
            document.getElementById("overlay").style.display = "block";
            document.getElementById('editModal').style.display = 'block';
            // Set the appointment ID in a hidden input field within the modal form
            document.getElementById('appointmentIdInput').value = appointmentId;
        }


        function closeModal2() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("editModal").style.display = "none";
        }
    </script>
</head>


<body>
    <header>
        <nav>
            <div class="logo">
                <a href="Home.php" aria-label="Homepage">
                    <img src="../../pics/Logo.png" alt="" class="src">
                </a>
            </div>
            <ul>
                <li class="welcomeName">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                <li><a href="Location.php">Our Location</a></li>
                <li><a href="">Dentist & Reviews</a></li>
                <li><a href="">Our Services</a></li>
                <li><a href="Calendar.php">Your Appointments</a></li>
                <li><a href="">Contact Us</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
                <li><a href="Request.php" class="btn-nav">Request an Appointment</a></li>
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
    
    <div class="table-container">
        <h2>Pending Appointments</h2>
        <a href="request.php"><button class="btn-nav">Schedule an appointment</button></a>
        <?php
        if (mysqli_num_rows($result_pending) > 0) {
        ?>
        <table>
            <?php
            while ($row = mysqli_fetch_assoc($result_pending)) {
                echo "<tr>";
                echo "<td>";
                echo "<h5>{$row['date_of_appointment']}</h5>";
                echo "<p>{$row['appointment_condition']}</p>";
                echo "</td>";
                // Adding buttons within a centered div
                echo "<td>";
                echo "<div style=\"text-align: center;\">";
                echo "<button class=\"btn-nav\"><a href=\"edit_appointment.php?id={$row['id']}\" style=\"text-decoration:none; color: inherit;\">Edit</a></button>";
                echo "<button class=\"btn-nav\" style=\"margin-left: 1rem;\" onclick=\"openModal('{$row['id']}')\">Cancel</button>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        } else {
            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 1rem;'>There are no pending appointments.</p>";

        }
        ?>
    </div>

    <div class="table-container">
        <h2>Appointments</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
        ?>
        <table>
        <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>";
                echo "<h5>{$row['date_of_appointment']}</h5>";
                echo "<p>{$row['appointment_condition']}</p>";
                echo "</td>";
                // Adding buttons within a centered div
                echo "<td>";
                echo "<div style=\"text-align: center;\">";
                echo "<button class=\"btn-nav\" style=\"margin-left: 6rem;\" onclick=\"openModal('{$row['id']}')\">Cancel</button>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }
        ?>
        </table>
        <?php
        } else {
            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 1rem;'>There are no appointments.</p>";
        }
        ?>
    </div>

    <div class="table-container">
        <h2>Finished Appointments</h2>
        <?php
        if (mysqli_num_rows($result_done) > 0) {
        ?>
        <table>
            <?php
            while ($row = mysqli_fetch_assoc($result_done)) {
                echo "<tr>";
                echo "<td>";
                echo "<h5>{$row['date_of_appointment']}</h5>";
                echo "<p>{$row['appointment_condition']}</p>";
                echo "</td>";
                /*
                echo "<td>";
                echo "<div class='column-item'>";
                echo "<button value='details'>Details</button>";
                echo "<select name='action' id='action_dpdown'>";
                echo "<option value='' selected disabled>Action</option>";
                echo "<option value='edit_app'>Edit Appointment</option>";
                echo "<option value='cancel_app'>Cancel Appointment</option>";
                echo "</select><br>";
                echo "</div>";
                echo "</td>";
                */
                echo "</tr>";
            }
            ?>
        </table>
        <?php
        } else {
            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 1rem;'>There are no finished appointments.</p>";
        }
        ?>
    </div>

    <?php
        if (mysqli_num_rows($result_cancel) > 0) {
    ?>
    <div class="table-container">
        <h2>Cancelled Appointments</h2>
        
        <table>
            <?php
            while ($row = mysqli_fetch_assoc($result_cancel)) {
                echo "<tr>";
                echo "<td>";
                echo "<h5>{$row['date_of_appointment']}</h5>";
                echo "<p>Reason:{$row['reason_of_cancel']}</p>";
                echo "</td>";
                /*
                echo "<td>";
                echo "<div class='column-item'>";
                echo "<button value='details'>Details</button>";
                echo "<select name='action' id='action_dpdown'>";
                echo "<option value='' selected disabled>Action</option>";
                echo "<option value='edit_app'>Edit Appointment</option>";
                echo "<option value='cancel_app'>Cancel Appointment</option>";
                echo "</select><br>";
                echo "</div>";
                echo "</td>";
                */
                echo "</tr>";
            }
            ?>
        </table>
        
    </div>
    <?php
    } else {
    }
    ?>

    <footer>
        <!-- place footer here -->
    </footer>

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
