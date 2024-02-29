<?php 
    session_start();
    include "../../db_conn.php";

    if (isset($_POST['submit'])) {
        $FirstName = $_POST['first_name'];
        $MiddleName = $_POST['middle_name'];
        $LastName = $_POST['last_name'];
        $Gender = $_POST['gender'];
        $Age = $_POST['age'];
        $ContactNumber = $_POST['contact_number'];
        $EmailAddress = $_POST['email_address'];
        $Weight = $_POST['weight'];
        $Allergies = $_POST['allergies'];
        $SpecifiedAllergies = $_POST['specified_allergies'];
        $Hypertension = $_POST['hypertension'];
        $Diabetes = $_POST['diabetes'];
        $UricAcid = $_POST['uric_acid'];
        $Cholesterol = $_POST['cholesterol'];
        $Asthma = $_POST['asthma'];
        $MedicallyCompromised = $_POST['medically_compromised'];

        if (empty($FirstName)) {
            header("Location: pCreate.php?error=First Name is required");
            exit();
        } else if(empty($LastName)) {
            header("Location: pCreate.php?error=Last Name is required");
            exit();
        } else if(empty($Gender)) {
            header("Location: pCreate.php?error=Gender is required");
            exit();
        } else if(empty($Age)) {
            header("Location: pCreate.php?error=Age is required");
            exit();
        } else if(empty($ContactNumber)) {
            header("Location: pCreate.php?error=Contact Number is required");
            exit();
        } else if(empty($EmailAddress)) {
            header("Location: pCreate.php?error=Email Address is required");
            exit();
        } else if(empty($Weight)) {
            header("Location: pCreate.php?error=Weight is required");
            exit();
        }else if(empty($Hypertension)){
            header("Location: pCreate.php?error=Fill out if you have Hypertension");
            exit();
        }else if(empty($Diabetes)){
            header("Location: pCreate.php?error=Fill out if you have Diabetes");
            exit();
        }else if(empty($UricAcid)){
            header("Location: pCreate.php?error=Fill out if you have High Uric Acid");
            exit();
        }else if(empty($Cholesterol)){
            header("Location: pCreate.php?error=Fill out if you have High Cholesterol");
            exit();
        }else if(empty($Asthma)){
            header("Location: pCreate.php?error=Fill out if you have Asthma");
            exit();
        }else if(empty($MedicallyCompromised)){
            header("Location: pCreate.php?error=Fill out if you are medicall compromised");
            exit();
        } else {
            $currentDateTime = date('Y-m-d H:i:s');
        
            // Retrieve the ID of the newest patient
            $getIDQuery = "SELECT patient_id FROM patients ORDER BY patient_id DESC LIMIT 1";
            $result = $conn->query($getIDQuery);
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $newestPatientID = $row['patient_id'];
        
                // Add one to the newest patient ID to generate the next patient ID
                $newPatientID = $newestPatientID + 1;

                echo $newestPatientID;

                $sql = "INSERT INTO `patients`(`patient_id`, `first_name`, `middle_name`, `last_name`, `age`,  `gender`, `weight`, `email_address`, `contact_number`, `created_at`) 
                    VALUES ('$newPatientID', \"$FirstName\", \"$MiddleName\",\"$LastName\",'$Age','$Gender','$Weight',\"$EmailAddress\",'$ContactNumber','$currentDateTime')";

                $insertResult = $conn->query($sql);

                $Msql = "INSERT INTO `medical_history`(`patient_id`, `allergies`, `specified_allergies`, `hypertension`, `diabetes`, `uric_acid`, `cholesterol`, `asthma`, `medically_compromised`, `created_at`) 
                VALUES ('$newPatientID','$Allergies','$SpecifiedAllergies', '$Hypertension','$Diabetes','$UricAcid','$Cholesterol','$Asthma','$MedicallyCompromised','$currentDateTime')";
        
                $insertResult2 = $conn->query($Msql);
        
                if ($insertResult && $insertResult2 === TRUE) {
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

    <!-- Back button outside the card, aligned with the left side of the container -->
    <a href="pTable.php"><button class="back-button">Back</button></a>
    
    <form action="" method="post">

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
            <span>Age:</span>
            <input type="number" name="age">
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
            <span>Weight(kg):</span>
            <input type="number" name="weight">
        </label><br>

        <hr><br>

        <p class="subtitle">Medical History</p><br>

        <label>
            <span>Does the patient have Allergies?</span>
            <input type="radio" name="allergies" value="yes" id="allergies_yes" onclick="showTextBox()"> 
            <label for="allergies_yes">Yes</label><br>

            <input type="radio" name="allergies" value="no" id="allergies_no" onclick="hideTextBox()"> 
            <label for="allergies_no">No</label>

            <div id="allergiesTextBox" class="hidden">
                <label for="specified_allergies">What are the allergies:</label>
                <input type="text" name="specified_allergies" id="specified_allergies"> 
            </div>
        </label>

        <script>
            function showTextBox() {
            document.getElementById('allergiesTextBox').style.display = 'block';
            }

            function hideTextBox() {
            document.getElementById('allergiesTextBox').style.display = 'none';
            }
        </script>

        <label>
            <span>Does the patient have Hypertension?</span>
            <input type="radio" name="hypertension" value="yes" id="hypertension_yes"> 
            <label for="hypertension_yes">Yes</label><br>
            
            <input type="radio" name="hypertension" value="no" id="hypertension_no">
            <label for="hypertension_no">No</label>
        </label>

        <label>
            <span>Does the patient have Diabetes?</span>
            <input type="radio" name="diabetes" value="yes" id="diabetes_yes">
            <label for="diabetes_yes">Yes</label><br>

            <input type="radio" name="diabetes" value="no" id="diabetes_no">
            <label for="diabetes_no">No</label>
        </label>

        <label>
            <span>Does the patient have High Uric Acid?</span>
            <input type="radio" name="uric_acid" value="yes" id="uric_acid_yes">
            <label for="uric_acid_yes">Yes</label><br>
            
            <input type="radio" name="uric_acid" value="no" id="uric_acid_no">
            <label for="uric_acid_no">No</label>
        </label>

        <label>
            <span>Does the patient have High Cholesterol?</span>
            <input type="radio" name="cholesterol" value="yes" id="cholesterol_yes">
            <label for="cholesterol_yes">Yes</label><br>
            
            <input type="radio" name="cholesterol" value="no" id="cholesterol_no">
            <label for="cholesterol_no">No</label>
        </label>

        <label>
            <span>Does the patient have Asthma?</span>
            <input type="radio" name="asthma" value="yes" id="asthma_yes">
            <label for="asthma_yes">Yes</label><br>
            
            <input type="radio" name="asthma" value="no" id="asthma_no">
            <label for="asthma_no">No</label>
        </label>

        <label>
            <span>Is the patient medically compromised?</span>
            <input type="radio" name="medically_compromised" value="yes" id="med_comp_yes">
            <label for="med_comp_yes">Yes (if yes, please seek clearance <br>or approval from your medical doctor)</label><br>
            
            <input type="radio" name="medically_compromised" value="no" id="med_comp_no">
            <label for="med_comp_no">No</label>
        </label>


        <button type="submit" name="submit">SAVE RECORD</button>
    </form>

</body>
</html>