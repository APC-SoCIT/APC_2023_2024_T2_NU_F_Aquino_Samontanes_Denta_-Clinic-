<?php 
session_start();
include '../../db_conn.php';

if (isset($_GET['id'])) {

    $PID = $_GET['id']; 
    $sql = "SELECT * FROM `patients` WHERE `patient_id`='$PID'";
    $result = $conn->query($sql); 

    if (isset($_POST['submit'])) {
        $DateOfAppointment = $_POST['date_of_appointment'];
        $timestamp = strtotime($DateOfAppointment);
        $DateOfAppointmentFormatted = date('m/d/y H:i', $timestamp);

        if(empty($DateOfAppointment)){
            header("Location: edit_appointment.php?error=Date of Appointment is required");
            exit();

        }else{
            $csql = "UPDATE appointments SET date_of_appointment = '$DateOfAppointmentFormatted' WHERE id = '$PID'";
            // Execute the SQL query
            $cresult = mysqli_query($conn, $csql);
            // Check if the query was successful
            if ($cresult) {
                header("Location: Calendar.php?success=Appointment updated");
                exit();
            } else {
                // Handle the error
            }
        }
    }

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

    $appointmentsql = "SELECT * FROM `appointments`";
    $appointmentresult = $conn->query($appointmentsql); 

    $dates = []; // Initialize an array to store appointment dates

    if ($appointmentresult->num_rows > 0) {        
        while ($row = $appointmentresult->fetch_assoc()) {
            $dates[] = $row['date_of_appointment']; // Add each date to the array
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
        
        // Attach a single change event listener to handle both restrictions
        dateTimePicker.addEventListener('change', function() {
            console.log('DateTimePicker value changed');

            var selectedDateTime = new Date(dateTimePicker.value);
            var selectedTime = selectedDateTime.getTime(); // Get time in milliseconds

            // Check if the selected time is within 30 minutes of any existing appointments
            var appointments = <?php echo json_encode($dates); ?>; // Assuming $appointmentDates contains the dates of existing appointments
            for (var i = 0; i < appointments.length; i++) {
                var appointmentTime = new Date(appointments[i]).getTime();
                if (Math.abs(selectedTime - appointmentTime) < 30 * 60 * 1000) {
                    // Display alert for appointment clash
                    showCustomAlert('Appointment already set by other patient. Please choose another date and time. You can choose 30 minutes ahead or before it.');
                    dateTimePicker.value = ''; // Reset date-time picker value
                    return; // Exit the loop once an appointment clash is found
                }
            }

            // Check if it's Sunday or time is outside 11am-6pm
            var dayOfWeek = selectedDateTime.getDay();
            var selectedHour = selectedDateTime.getHours();
            if (dayOfWeek === 0) {
                showCustomAlert('Sundays are not allowed!');
                dateTimePicker.value = ''; // Reset date-time picker value
            } else if (selectedHour < 11 || selectedHour >= 18) {
                showCustomAlert('Please select a time between 11am and 6pm.');
                dateTimePicker.value = ''; // Reset date-time picker value
            }
        });

        // Function to display custom alert
        function showCustomAlert(message) {
            console.log('Showing custom alert:', message); // Add this line for debugging

            var alertBox = document.createElement('div');
            alertBox.className = 'custom-alert';
            alertBox.textContent = message;
            document.body.appendChild(alertBox);
            // Automatically remove the alert after 3 seconds
            setTimeout(function() {
                alertBox.parentNode.removeChild(alertBox);
            }, 3000);
        }
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

    
    <form action="" method="post">
        <label>
            <span>Date of Appointment</span>
            <div class="center">
                <button type="button" onclick="openPopup()" class="choose_date_bttn">Choose Date and Time</button><br><br>
            </div>
            <input type="hidden" name="date_of_appointment" id="selectedDateTime">
            Selected Date and Time: <br><br><div id="selectedDateTimeDisplay"></div>
        </label><br>

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