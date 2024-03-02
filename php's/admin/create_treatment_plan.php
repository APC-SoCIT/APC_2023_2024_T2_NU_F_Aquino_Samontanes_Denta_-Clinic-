<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['patient_id'])) {
    $ID = $_GET['patient_id']; 
    
    $plansql = "SELECT * FROM `treatment_plan` WHERE `id`='$ID'";
    $planresult = $conn->query($plansql); 

    if ($planresult->num_rows > 0) {        
        while ($row = $planresult->fetch_assoc()) {
            $ID = $row['id'];
            $PID = $row['patient_id'];
            $TreatmentPlan = $row['treatment_plan'];
            $Progress = $row['progress'];
            $Prophylaxis = $row['Prophylaxis'];
            $Date = $row['date'];
            $Comment = $row['comments'];
        }
    }

    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$ID'";
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

    if(isset($_POST["xray_submit"])) {
        // File upload path
        $targetDir = "../../pics/xrays/";
        
        // Loop through each file
        foreach($_FILES['photos']['name'] as $key=>$val){
            $fileName = basename($_FILES["photos"]["name"][$key]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
            // Allow certain file formats
            $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
            if(in_array($fileType, $allowedTypes)) {
                // Upload file to server
                if(move_uploaded_file($_FILES["photos"]["tmp_name"][$key], $targetFilePath)) {
                    // Insert image file name into database
                    $insert = $conn->query("INSERT into xrays (treatment_plan_id, xray_film, created_at) VALUES ('$ID', '".$fileName."', NOW())");
                    if(!$insert) {
                        echo "File upload failed, please try again.";
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
            }
        }
    }

    if(isset($_POST["submit"])) {
        $PatientID = $PID;
        $TreatmentPlan = $_POST['dropdown'];
        $Progress = $_POST['Progress'];
        $Prophylaxis = $_POST['Prophylaxis'];
        $Date = $_POST['date'];
        $timestamp = strtotime($Date);
        $DateFormatted = date('m/d/y H:i', $timestamp);
        $Comment = $_POST['comments'];
        

        if (empty($TreatmentPlan)) {
            header("Location: create_treatment_plan.php?id=$ID&error=Treatment Plan is required");
            exit();
        } elseif (empty($Progress)) {
            header("Location: create_treatment_plan.php?id=$ID&error=Progress is required");
            exit();
        } elseif (empty($Prophylaxis)) {
            header("Location: create_treatment_plan.php?id=$ID&error=Prophylaxis is required");
            exit();
        } elseif (empty($Date)) {
            header("Location: create_treatment_plan.php?id=$ID&error=Date of Treatments is required");
            exit();
        } else {
            // Prepare SQL statement for updating data
            $updatesql = "INSERT INTO `treatment_plan` (`patient_id`, `treatment_plan`, `progress`, `date`, `Prophylaxis`, `comments`) VALUES ('$PatientID', '$TreatmentPlan', '$Progress', '$DateFormatted', '$Prophylaxis', '$Comment')";

            if ($conn->query($updatesql) === TRUE) {
                header("Location: pView.php?patient_id=$PID&success=Treatment Plan Created");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $conn->close();
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Aquino Samontanes Dental Clinic</title>
        <link rel="stylesheet" type="text/css" href="../../css's/admin/CreateTP.css">
        <script>
            // JavaScript functions to open and close the modal
            function openModal() {
                document.getElementById("overlay").style.display = "block";
                document.getElementById("uploadModal").style.display = "block";
            }

            function closeModal() {
                document.getElementById("overlay").style.display = "none";
                document.getElementById("uploadModal").style.display = "none";
            }
        </script>
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
                <li class="dropdown">
                    <a href="CheckAppointments.php" class="dropbtn">Appointments</a>
                    <div class="dropdown-content">
                        <a href="CheckAppointments.php">Check Appointments</a>
                        <a href="Calendar.php">Appointment Calendar</a>
                        <a href="FinishedAppts.php">Finished Appointments</a>
                        <a href="CancelledAppts.php">Cancelled Appointments</a>
                    </div>
                </li>
                <li><a href="pTable.php" class="sel_page">Patient Records Table</a></li>
                <li><a href="ArchivedRecords.php">Archived Records</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
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

    <!-- Center the container horizontally -->
    <div class="container">
        <!-- Back button outside the card, aligned with the left side of the container -->
        <a href="pView.php?patient_id=<?php echo $PID; ?>"><button class="back-button">Back</button></a>
        <div class="card">
            <div class="card-content">
                <div class="column1">

                    <form action="" method="post">

                    <label for="dropdown">Treatment Plan:
                    <select id="dropdown" name="dropdown">
                        <option value="Jacket Crown Preparation">Jacket Crown Preparation</option>
                        <option value="Teeth Restoration">Teeth Restoration</option>
                        <option value="Root Canal Treatment">Root Canal Treatment</option>
                        <option value="Surgery">Surgery</option>
                        <option value="Teeth Extraction">Teeth Extraction</option>
                        <option value="Removal Denture Construction">Removal Denture Construction</option>
                    </select>
                    </label>

                    <label>
                        <span>Progress:</span>
                        <input type="radio" name="Progress" value="Ongoing" id="Progress_Ongoing"> 
                        <label for="Progress_Ongoing">Ongoing</label><br>
                        
                        <input type="radio" name="Progress" value="Done" id="Progress_Done">
                        <label for="Progress_Done">Done</label><br>

                        <input type="radio" name="Progress" value="Cancelled" id="Progress_Cancelled">
                        <label for="Progress_Cancelled">Cancelled</label>
                    </label>

                    <label>
                        <span>Oral Prophylaxis:</span>
                        <input type="radio" name="Prophylaxis" value="yes" id="Prophylaxis_yes"> 
                        <label for="Prophylaxis_yes">Yes</label><br>

                        <input type="radio" name="Prophylaxis" value="no" id="Prophylaxis_no"> 
                        <label for="Prophylaxis_no">No</label>
                    </label>

                    <label>
                        <span>Date of Treatment:</span>
                        <input type="datetime-local" id="dateTimePicker" name="date">
                    </label>

                    <label>
                        <span>Comments: </span>
                        <textarea name="comments" rows="3" cols="50"style="resize: none;"></textarea>
                    </label><br>

                    <button type="submit" name="submit" class="edit-button">Save</button>

                    </form>


                </div>
                <div class="column2">
                    <!-- Button to open the modal -->
                    <button onclick="openModal()">Upload X-ray Films</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="overlay" id="overlay" onclick="closeModal()"></div> <!-- Overlay -->
    <div class="modal" id="uploadModal">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Upload Photos</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="photos[]" id="photos" accept="image/*" class="upload_bttn" multiple>
            <input type="xray_submit" value="Upload" name="xray_submit" class="upload_bttn">
        </form>
    </div>

</body>
</html>

    <?php
    } else{ 
        header('Location: pTable.php');
    } 
?> 