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
        AND (appointment_condition = 'approved' OR appointment_condition = 'disapproved')
        GROUP BY appointment_condition
        ORDER BY date_of_appointment";


    $result_pending = mysqli_query($conn, $sql_pending);
    if (!$result_pending) {
        die("Error: " . mysqli_error($conn));
    }

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
    <!-- Load Bootstrap CSS first -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css's/patient/Calendar.css">
</head>


<body>
    <header>
        <nav>
            <div class="logo">
                <a href="patient.php" aria-label="Homepage">
                    <img src="../../pics/Logo.png" alt="" class="src">
                </a>
            </div>
            <ul>
                <li class="welcomeName">Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></li>
                <li><a href="">Call a Clinic</a></li>
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
            echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 1rem;'>There are no appointments.</p>";
        }
        ?>
    </div>
    <footer>
        <!-- place footer here -->
    </footer>

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
