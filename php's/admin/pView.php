<?php 
session_start();
include '../../db_conn.php';

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
            $TreatmentPlan = $row['treatment_plan'];
            $Progress = $row['progress'];
        }
    }
        
    $plansql = "SELECT * FROM `treatment_plan` WHERE `patient_id`='$PID'";
    $planresult = $conn->query($plansql); 

    

?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/View.css">
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


    <div class="container">
        <div class="left-div">
            <form action="" method="post">
                <?php if (isset($_GET['error'])) { ?>
                        <p class="error"><?php echo $_GET['error']; ?></p>
                    <?php } ?>
                    <?php if (isset($_GET['success'])) { ?>
                        <p class="success"><?php echo $_GET['success']; ?></p>
                    <?php } ?> 

            <header><?php echo $FirstName; ?> <?php echo $MiddleName; ?> <?php echo $LastName; ?></header>

            <div class="info-item">
                <span class="info-label">Gender:</span>
                <span>
                    <?php 
                        $Gender = 'M'; // Example value for demonstration
                        echo ($Gender == 'M') ? 'Male' : '';
                        echo ($Gender == 'F') ? 'Female' : ''; 
                    ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Email Address:</span>
                <span><?php echo $EmailAddress; ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Contact Number:</span>
                <span><?php echo $ContactNumber; ?></span>
            </div>

        </form>

        </div>
        <div class="right-div">
            <!-- Content for the right div -->
            <header>Treatment Plans</header>
            <div class="card-container">
                <!-- Check if there are treatment plans -->
                <?php
                $num_rows = $planresult->num_rows;
                if ($num_rows > 0) {
                    $count = 0;
                    while ($row = $planresult->fetch_assoc()) {
                        $count++;
                        $PID = $row['patient_id'];
                        $TreatmentPlan = $row['treatment_plan'];
                        $Progress = $row['progress'];
                        $Date = $row['date'];
                        // Generate a clickable card for each treatment plan
                        ?>
                        <a href="details.php?pid=<?php echo $PID; ?>" class="card">
                            <p><strong>Treatment Plan:</strong> <?php echo $TreatmentPlan; ?></p>
                            <p><strong>Progress:</strong> <?php echo $Progress; ?></p>
                            <p><strong>Date:</strong> <?php echo $Date; ?></p>
                        </a>
                        <?php
                        // Check if it's the last iteration
                        if ($count == $num_rows) {
                            // Generate a card for creating a new treatment plan
                            ?>
                            <a href="create_treatment_plan.php" class="card">
                                <h3>Create New Treatment Plan</h3>
                                <p>Click here to create a new treatment plan</p>
                            </a>
                            <?php
                        }
                    }
                } else {
                    // If no treatment plans found
                    echo "<p>No treatment plans available.</p>";
                }
                ?>
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