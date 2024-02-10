<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['id'])) {
    $ID = $_GET['id']; 
    
    $plansql = "SELECT * FROM `treatment_plan` WHERE `id`='$ID'";
    $planresult = $conn->query($plansql); 

    if ($planresult->num_rows > 0) {        
        while ($row = $planresult->fetch_assoc()) {
            $PID = $row['patient_id'];
            $TreatmentPlan = $row['treatment_plan'];
            $Progress = $row['progress'];
            $Date = $row['date'];
            $Comment = $row['comments'];
        }
    }

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
    }

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/ViewTP.css">
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

    <!-- Center the container horizontally -->
    <div class="container">
        <!-- Back button outside the card, aligned with the left side of the container -->
        <a href="pView.php?patient_id=<?php echo $PID; ?>"><button class="back-button">Back</button></a>
        <!-- Edit button outside the card, aligned with the right side of the container -->
        <a href="view.html?id=<?php echo $ID; ?>"><button class="edit-button">Edit</button></a>
        <!-- Single card with two columns inside -->
        <div class="card">
            <div class="card-content">
                <div class="column1">
                    <h2><?php echo $FirstName; ?> <?php echo $MiddleName; ?> <?php echo $LastName; ?></h2>
                    <p><strong>Treatment Plan:</strong> <?php echo $TreatmentPlan; ?></p>
                    <p><strong>Progress:</strong> <?php echo $Progress; ?></p>
                    <p><strong>Date:</strong> <?php echo $Date; ?></p>
                </div>
                <div class="column2">
                    <img class="image" src="../../pics/teeth_xray.jpeg" alt="Teeth Xray">
                    <h4>Comments</h4>
                    <div class="comments-box">
                        <textarea name="comments" rows="3" cols="50" readonly style="resize: none;"><?php echo $Comment; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

    <?php
    } else{ 
        header('Location: pTable.php');
    } 
?> 