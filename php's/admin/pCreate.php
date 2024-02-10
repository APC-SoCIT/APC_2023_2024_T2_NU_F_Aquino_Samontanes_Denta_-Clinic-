<?php 
    session_start();
    include "../../db_conn.php";

    if (isset($_POST['submit'])) {
        $FirstName = $_POST['first_name'];
        $MiddleName = $_POST['middle_name'];
        $LastName = $_POST['last_name'];
        $Gender = $_POST['gender'];
        $ContactNumber = $_POST['contact_number'];
        $EmailAddress = $_POST['email_address'];
        $TreatmentPlan = $_POST['treatment_plan'];
        $Progress = $_POST['progress'];

        if (empty($FirstName)) {
            header("Location: pCreate.php?error=First Name is required");
            exit();
        } else if(empty($LastName)) {
            header("Location: pCreate.php?error=Last Name is required");
            exit();
        } else if(empty($Gender)) {
            header("Location: pCreate.php?error=Gender is required");
            exit();
        } else if(empty($ContactNumber)) {
            header("Location: pCreate.php?error=Contact Number is required");
            exit();
        } else if(empty($EmailAddress)) {
            header("Location: pCreate.php?error=Email Address is required");
            exit();
        } else {
            $currentDateTime = date('Y-m-d H:i:s');
        
            // Retrieve the ID of the newest patient
            $getIDQuery = "SELECT patient_id FROM patients ORDER BY created_at DESC LIMIT 1";
            $result = $conn->query($getIDQuery);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $newestPatientID = $row['patient_id'];
        
                // Add one to the newest patient ID to generate the next patient ID
                $newPatientID = $newestPatientID + 1;
        
                $sql = "INSERT INTO `patients`(`patient_id`, `first_name`, `middle_name`, `last_name`, `gender`, `contact_number`, `email_address`, `treatment_plan`, `progress`, `created_at`) VALUES ('$newPatientID', '$FirstName','$MiddleName','$LastName','$Gender','$ContactNumber','$EmailAddress','$TreatmentPlan','$Progress', '$currentDateTime')";
        
                $insertResult = $conn->query($sql);
        
                if ($insertResult === TRUE) {
                    header("Location: pCreate.php?success=Data Successfully Created!");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                // Handle the case where no patients exist yet
                echo "Error: No existing patients found.";
            }
    
        }
        $conn->close(); 
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

    <form action="" method="post">
    <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?> 

        <header>NEW PATIENT PROFILE</header>

        <label>
        <span>First Name:</span>
            <input type="text" name="first_name">
        </label><br>

        <label>
            <span>Middle Name:</span>
            <input type="text" name="middle_name">
        </label><br>

        <label>
            <span>Last Name:</span>
            <input type="text" name="last_name">
        </label><br>

        <label>
            <span>Gender:</span>
            <input type="radio" name="gender" value="M" id="gender_male"> 
            <label for="gender_male">Male</label><br>
            
            <input type="radio" name="gender" value="F" id="gender_female"> 
            <label for="gender_female">Female</label>
        </label><br>

        <label>
            <span>Contact Number:</span>
            <input type="number" name="contact_number">
        </label><br>
        
        <label>
            <span>Email Address:</span>
            <input type="text" name="email_address">
        </label><br>

        <label>
            <span>Treatment Plan:</span>
            <input type="text" name="treatment_plan">
        </label><br>

        <label>
            <span>Progress Tracking:</span>
            <input type="text" name="progress">
        </label><br>

        <button type="submit" name="submit">SAVE RECORD</button>
    </form>

</body>
</html>