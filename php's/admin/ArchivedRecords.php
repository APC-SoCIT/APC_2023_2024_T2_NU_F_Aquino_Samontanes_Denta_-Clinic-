<?php
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $sql = "SELECT DISTINCT patient_id, first_name, middle_name, last_name, gender, contact_number
            FROM patients 
            WHERE is_archived = 'yes'";

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
        <h2 class="title">Archived Records</h2>
        <a href="pCreate.php" class="no-underline">
            <button id="plusbtn">&#43;</button>
        </a>
    </div>
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
                    echo '<div id="myModal" class="modal">';
                    echo '<h2>Confirmation</h2>';
                    echo '<p>Are you sure you want to retrieve the record back?</p>';
                    echo '<button onclick="cancelAction();">Cancel</button>';
                    echo '<a href="pRetrieve.php?patient_id=' . $row['patient_id'] . '">Proceed</a>';
                    echo '</div>';
                    echo "<tr>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['middle_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['gender']}</td>";
                    echo "<td>{$row['contact_number']}</td>";
                    echo "<td style='margin: auto;'>
                    <a href='#' class='custom-link' onclick='openModal();'><button>Retrieve</button></a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
    } else {
        echo "<p style='text-align: center; font-size: 18px; color: #555; background-color: #f7f7f7; padding: 10px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>There are no archived patients</p>";
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
