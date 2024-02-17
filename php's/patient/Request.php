<?php 
session_start();
include '../../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    $PID = $_SESSION['id']; 
    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$PID'";
    $result = $conn->query($sql); 

    if ($result->num_rows > 0) {        
        while ($row = $result->fetch_assoc()) {
            $PID = $row['patient_id'];
            $FirstName = $row['first_name'];
            $MiddleName = $row['middle_name'];
            $LastName = $row['last_name'];
            $LastVisit = $row['last_visit'];
            $Age = $row['age'];
            $Gender = $row['gender'];
            $ContactNumber = $row['contact_number'];
            $EmailAddress = $row['email_address'];
            $Weight = $row['weight'];
        }
    }
    
    $mediaclsql = "SELECT * FROM `medical_history` WHERE `patient_id`='$PID' ORDER BY `created_at` DESC LIMIT 1";
    $medicalresult = $conn->query($mediaclsql); 

    if ($medicalresult->num_rows > 0) {        
        while ($row = $medicalresult->fetch_assoc()) {
            $Concerns = $row['concerns'];
            $Allergies = $row['allergies'];
            $SpecifiedAllergies = $row['specified_allergies'];
            $Hypertension = $row['hypertension'];
            $Diabetes = $row['diabetes'];
            $UricAcid = $row['uric_acid'];
            $Cholesterol = $row['cholesterol'];
            $Asthma = $row['asthma'];
            $MedicallyCompromised = $row['medically_compromised'];
        }
    }

    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../../css's/patient/request.css">
    <style>
        /* Add this style to prevent resizing of the textarea */
        textarea {
            resize: none;
        }
        .hidden {
            display: none;
        }
        #allergiesTextBox {
        margin-bottom: 0px; /* Adjust the negative margin to compensate for default spacing */
         }
    </style>
    
    <script>
       // Function to open the popup
        function openPopup() {
            document.getElementById("overlay").style.display = "block";
            document.getElementById("dateTimePopup").style.display = "block";
            document.body.style.overflow = "hidden";
            disableForm();
        }

        // Function to select date and time and close the popup
        function selectDateTime() {
            var selectedDateTime = document.getElementById("dateTimePicker").value;
            document.getElementById("selectedDateTime").value = selectedDateTime;
            document.getElementById("selectedDateTimeDisplay").innerHTML = formatDateTime(selectedDateTime);
            document.getElementById("overlay").style.display = "none";
            document.getElementById("dateTimePopup").style.display = "none";
            document.body.style.overflow = "visible";
            enableForm();
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById("overlay").style.display = "none";
            document.getElementById("dateTimePopup").style.display = "none";
            document.body.style.overflow = "visible";
            enableForm();
        }

        // Function to disable form elements
        function disableForm() {
            var formElements = document.getElementById("myForm").elements;
            for (var i = 0; i < formElements.length; i++) {
                formElements[i].disabled = true;
            }
        }

        // Function to enable form elements
        function enableForm() {
            var formElements = document.getElementById("myForm").elements;
            for (var i = 0; i < formElements.length; i++) {
                formElements[i].disabled = false;
            }
        }

        // Function to format selected date and time
        function formatDateTime(dateTime) {
            var selectedDateTime = new Date(dateTime);
            var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
            return selectedDateTime.toLocaleDateString('en-US', options);
        }

        // Function to restrict selection of Sundays and times outside 11am-6pm
        document.addEventListener('DOMContentLoaded', function() {
            var dateTimePicker = document.getElementById('dateTimePicker');
            
            // Disable Sundays
            dateTimePicker.addEventListener('input', function() {
                var selectedDate = new Date(dateTimePicker.value);
                var dayOfWeek = selectedDate.getDay();
                if (dayOfWeek === 0) {
                    alert('Sundays are not allowed!');
                    dateTimePicker.value = ''; // Reset date-time picker value
                }
            });

            // Restrict time to 11am-6pm
            dateTimePicker.addEventListener('change', function() {
                var selectedTime = dateTimePicker.value.split('T')[1];
                var selectedHour = parseInt(selectedTime.split(':')[0]);
                if (selectedHour < 11 || selectedHour >= 18) {
                    alert('Please select a time between 11am and 6pm.');
                    dateTimePicker.value = ''; // Reset date-time picker value
                }
            });
        });
    </script>
    


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
                <li><a href="request.php" class="btn-nav">Request an Appointment</a></li>
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

    
    <form action="submit_appointment.php" method="post">

        <label>
            <span>First Name</span>
            <input type="text" name="first_name" value="<?php echo $FirstName ?>">
        </label><br>

        <label>
            <span>Middle Name</span>
            <input type="text" name="middle_name" value="<?php echo $MiddleName ?>">
        </label><br>

        <label>
            <span>Last Name</span>
            <input type="text" name="last_name" value="<?php echo $LastName ?>">
        </label><br>

        <label>
            <span>When was your last visit at a dental clinic?</span>
            <input type="radio" name="last_visit" value="recently" id="recently">
            <label for="recently">Recently (less than a month ago)</label><br>

            <input type="radio" name="last_visit" value="one_to_six_months_ago" id="one_to_six_months_ago">
            <label for="one_to_six_months_ago">1 to 6 months ago</label><br>

            <input type="radio" name="last_visit" value="seven_to_twelve_months_ago" id="seven_to_twelve_months_ago">
            <label for="seven_to_twelve_months_ago">7 to 12 months ago</label><br>

            <input type="radio" name="last_visit" value="over_a_year_ago" id="over_a_year_ago">
            <label for="over_a_year_ago">Over a year ago</label><br>
        </label><br>


        <label>
            <span>Age</span>
            <input type="number" name="age" value="<?php echo $Age ?>">
        </label><br>

        <label>
            <span>Contact Number</span>
            <input type="number" name="Contact_Number" value="<?php echo $ContactNumber ?>">
        </label><br>
        
        <label>
            <span>Email</span>
            <input type="text" name="email_address" value="<?php echo $EmailAddress ?>">
        </label><br>

        <label>
            <span>Weight(kg)</span>
            <input type="number" name="weight" value="<?php echo $Weight ?>">
        </label><br>

        <label>
            <span>Date of Appointment</span>
            <div class="center">
                <button type="button" onclick="openPopup()" class="choose_date_bttn">Choose Date and Time</button><br><br>
            </div>
            <input type="hidden" name="date_of_appointment" id="selectedDateTime">
            Selected Date and Time: <br><br><div id="selectedDateTimeDisplay"></div>
        </label><br>

        <label>
            <span>Gender</span>
            <input type="radio" name="gender" value="M" id="gender_male" <?php if ($Gender === "M") echo "checked"; ?>>
            <label for="gender_male">Male</label><br>
            
            <input type="radio" name="gender" value="F" id="gender_female" <?php if ($Gender === "F") echo "checked"; ?>>
            <label for="gender_female">Female</label>
        </label><br>


        <label>
            <span>Your Concerns</span>
            <textarea name="concerns" rows="3" cols="50"></textarea>
        </label><br>

        <label>
            <span>Do you have allergies?</span>
            
            <input type="radio" name="allergies" value="yes" id="allergies_yes" onclick="showTextBox()" <?php if ($Allergies === "yes") echo "checked"; ?>> 
            <label for="allergies_yes">Yes</label>

            <input type="radio" name="allergies" value="no" id="allergies_no" onclick="hideTextBox()" <?php if ($Allergies === "no") echo "checked"; ?>> 
            <label for="allergies_no">No</label>

            <div id="allergiesTextBox" class="hidden">
                <label for="specified_allergies">Enter your allergies:</label>
                <input type="text" name="specified_allergies" id="specified_allergies" <?php echo $SpecifiedAllergies?>> 
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
            <span>Do you have Hypertension?</span>
            <input type="radio" name="hypertension" value="yes" id="hypertension_yes" <?php if ($Hypertension === "yes") echo "checked"; ?>> 
            <label for="hypertension_yes">Yes</label><br>
            
            <input type="radio" name="hypertension" value="no" id="hypertension_no" <?php if ($Hypertension === "no") echo "checked"; ?>>
            <label for="hypertension_no">No</label>
        </label>

        <label>
            <span>Do you have Diabetes?</span>
            <input type="radio" name="diabetes" value="yes" id="diabetes_yes" <?php if ($Diabetes === "yes") echo "checked"; ?>>
            <label for="diabetes_yes">Yes</label><br>

            <input type="radio" name="diabetes" value="no" id="diabetes_no" <?php if ($Diabetes === "no") echo "checked"; ?>>
            <label for="diabetes_no">No</label>
        </label>

        <label>
            <span>Do you have High Uric Acid?</span>
            <input type="radio" name="uric_acid" value="yes" id="uric_acid_yes" <?php if ($UricAcid === "yes") echo "checked"; ?>>
            <label for="uric_acid_yes">Yes</label><br>
            
            <input type="radio" name="uric_acid" value="no" id="uric_acid_no" <?php if ($UricAcid === "no") echo "checked"; ?>>
            <label for="uric_acid_no">No</label>
        </label>

        <label>
            <span>Do you have High Cholesterol?</span>
            <input type="radio" name="cholesterol" value="yes" id="cholesterol_yes" <?php if ($Cholesterol === "yes") echo "checked"; ?>>
            <label for="cholesterol_yes">Yes</label><br>
            
            <input type="radio" name="cholesterol" value="no" id="cholesterol_no" <?php if ($Cholesterol === "no") echo "checked"; ?>>
            <label for="cholesterol_no">No</label>
        </label>

        <label>
            <span>Do you have Asthma?</span>
            <input type="radio" name="asthma" value="yes" id="asthma_yes" <?php if ($Asthma === "yes") echo "checked"; ?>>
            <label for="asthma_yes">Yes</label><br>
            
            <input type="radio" name="asthma" value="no" id="asthma_no" <?php if ($Asthma === "no") echo "checked"; ?>>
            <label for="asthma_no">No</label>
        </label>

        <label>
            <span>Are you medically compromised?</span>
            <input type="radio" name="medically_compromised" value="yes" id="med_comp_yes" <?php if ($MedicallyCompromised === "yes") echo "checked"; ?>>
            <label for="med_comp_yes">Yes (if yes, please seek clearance <br>or approval from your medical doctor)</label><br>
            
            <input type="radio" name="medically_compromised" value="no" id="med_comp_no" <?php if ($MedicallyCompromised === "no") echo "checked"; ?>>
            <label for="med_comp_no">No</label>
        </label>

        <button type="submit" name="submit" class="submit_button">Submit</button>
    </form>

    <div class="overlay" id="overlay" onclick="closePopup()"></div> <!-- Overlay -->
    <div class="popup" id="dateTimePopup">
        <h2>Select Date and Time</h2>
        <p class="popup_txt">We're open from <b>11am to 6pm</b> every day except <b style="color:red;">Sundays</b>.</p><br>
        <input type="datetime-local" id="dateTimePicker">
        <button onclick="selectDateTime()" class="select_bttn">Select</button>
        <button onclick="closePopup()" class="close_bttn">Close</button>
    </div>

</body>
</html>

<?php
}else{
    header("Location: ../../index.php");
    exit();
}
?>