<?php
session_start();
include '../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $sql = "SELECT DISTINCT patient_id, first_name, middle_name, last_name, gender, contact_number
            FROM tbl_patients ";

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
                    <li><a href="">Patient Records Table</a></li>
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
        <table border="1">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Contact Number</th>
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
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['contact_number']}</td>";
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<button type='submit' name='accept' value='{$row['patient_id']}'>Accept</button>";
                    echo "<button type='submit' name='disapprove' value='{$row['patient_id']}'>Disapprove</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>There are no patients in the table.</p>";
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
