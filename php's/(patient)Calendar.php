<?php
session_start();
include '../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accept'])) {
            $appointmentId = $_POST['accept'];
            updateAppointmentStatus($conn, $appointmentId, 'approved');
        } elseif (isset($_POST['disapprove'])) {
            $appointmentId = $_POST['disapprove'];
            updateAppointmentStatus($conn, $appointmentId, 'disapproved');
        }
    }

    $PatientID = $_SESSION['id'];
    $sql = "SELECT *
            FROM tbl_request 
            WHERE patient_id = $PatientID";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
?>

<!doctype html>
<html lang="en">

<head>
    <title>Aquino Samontanes Dental Clinic</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css's/pCalendar.css">
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main>

        <div class="table-container">
            <h2>Appointments</h2>
            <a href="request.php"><button>Schedule Appointments</button></a>
            <table>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>";
                    echo "<h5>{$row['date_of_appointment']}</h5>";
                    echo "<p>{$row['appointment_condition']}</p>";
                    echo "</td>";
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
                    echo "</tr>";
                }
                ?>
            </table>
        </div>

    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>

<?php
} else {
    header("Location: ../index.php");
    exit();
}
?>
