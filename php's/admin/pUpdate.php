<?php 
session_start();
include '../../db_conn.php';

if (isset($_POST['submit'])) {
    $PID = $_POST['patient_id'];
    $FirstName = $_POST['first_name'];
    $MiddleName = $_POST['middle_name'];
    $LastName = $_POST['last_name'];
    $Gender = $_POST['gender'];
    $ContactNumber = $_POST['contact_number'];
    $EmailAddress = $_POST['email_address'];

    $sql = "UPDATE `patients` SET `first_name`='$FirstName',`middle_name`='$MiddleName',`last_name`='$LastName',`gender`='$Gender',`contact_number`='$ContactNumber',`email_address`='$EmailAddress' WHERE `patient_id`='$PID'";

    $result = $conn->query($sql); 
    if ($result == TRUE) {
        $update_success = true;
        header("Location: pTable.php?success=Patient Record Updated");
                exit();
    }else{
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
} 

if (isset($_GET['patient_id'])) {
    $PID = $_GET['patient_id']; 
    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$PID'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $PID = $row['patient_id'];
            $FirstName = $row['first_name'];
            $MiddleName = $row['middle_name'];
            $LastName = $row['last_name'];
            $Gender = $row['gender'];
            $ContactNumber = $row['contact_number'];
            $EmailAddress = $row['email_address'];
        } 
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/Create.css">
    </head>
<html>
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

<!-- Back button outside the card, aligned with the left side of the container -->
<a href="pTable.php"><button class="back-button">Back</button></a>

    <form action="" method="post">
    <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?> 

        <header>UPDATE PATIENT PROFILE</header>

        <label>
        <span>First Name:</span>
            <input type="text" name="first_name" value="<?php echo $FirstName; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $PID; ?>">
        </label><br>

        <label>
            <span>Middle Name:</span>
            <input type="text" name="middle_name" value="<?php echo $MiddleName; ?>">
        </label><br>

        <label>
            <span>Last Name:</span>
            <input type="text" name="last_name" value="<?php echo $LastName; ?>">
        </label><br>

        <label>
            <span>Gender:</span>
            <input type="radio" name="gender" value="M" id="gender_male" <?php echo ($Gender == 'M') ? 'checked' : ''; ?>>
            <label for="gender_male">Male</label><br>

            <input type="radio" name="gender" value="F" id="gender_female" <?php echo ($Gender == 'F') ? 'checked' : ''; ?>>
            <label for="gender_female">Female</label>
        </label><br>

        <label>
            <span>Contact Number:</span>
            <input type="number" name="contact_number" value="<?php echo $ContactNumber; ?>">
        </label><br>
        
        <label>
            <span>Email Address:</span>
            <input type="text" name="email_address" value="<?php echo $EmailAddress; ?>">
        </label><br>

        <button type="submit" name="submit">SAVE RECORD</button>
    </form>

</body>
</html>

    <?php
    } else{ 
        header('Location: pTable.php');
    } 
}
?> 