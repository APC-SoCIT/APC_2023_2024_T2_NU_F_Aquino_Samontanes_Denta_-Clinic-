<?php 
session_start();
include '../db_conn.php';

if (isset($_SESSION['id']) && isset($_SESSION['email_address'])) {

    ?>
<!DOCTYPE html>
<html>
<head>
    <title>Aquino Samontanes Dental Clinic</title>
    <link rel="stylesheet" type="text/css" href="../css's/request.css">
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
        document.addEventListener('DOMContentLoaded', function () {
            // Get the date input element
            var dateInput = document.querySelector('input[name="date_of_appointment"]');

            // Set the default date and time to today at 11 AM
            var defaultDate = new Date();
            defaultDate.setHours(19, 0, 0, 0);

            // Format the default date for the input field
            var defaultDateString = defaultDate.toISOString().slice(0, 16);

            // Set the default value for the date input
            dateInput.value = defaultDateString;

            var lastSelectedTime = null; // Track the last selected time

            // Add an event listener to the date input
            dateInput.addEventListener('input', function () {
            // Check if a valid date is selected
            if (!dateInput.value) {
                return;
            }

            // Parse the selected date and time
            var selectedDateTime = new Date(dateInput.value);

            // Check if the selected date is a Sunday (day of the week is 0)
            if (selectedDateTime.getDay() === 0) {
                alert('Sundays are not allowed. Please choose another date.');
                dateInput.value = ''; // Clear the input field
                return;
            }

            // Check if the selected time is different from the last selected time
            var selectedTime = selectedDateTime.getHours();
            if (selectedTime !== lastSelectedTime) {
                lastSelectedTime = selectedTime; // Update the last selected time

                // Check if the selected time is before 11 am or after 6 pm
                if (selectedTime < 11 || selectedTime >= 18) {
                    alert('Please choose a time between 11 am and 6 pm.');
                    dateInput.value = ''; // Clear the input field
                }
            }
            });
        });
    </script>


</head>
<body>
    <form action="submit_appointment.php" method="post">
    <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <label>
            <span>First Name</span>
            <input type="text" name="first_name" value="<?php echo $_SESSION['first_name']; ?>" readonly>
        </label><br>

        <label>
            <span>Middle Name</span>
            <input type="text" name="middle_name">
        </label><br>

        <label>
            <span>Last Name</span>
            <input type="text" name="last_name" value="<?php echo $_SESSION['last_name']; ?>" readonly>
        </label><br>

        <label>
            <span>Contact Number</span>
            <input type="text" name="Contact_Number">
        </label><br>
        
        <label>
            <span>Email</span>
            <input type="text" name="email_address" value="<?php echo $_SESSION['email_address']; ?>" readonly>
        </label><br>

        <label>
            <span>Weight(kg)</span>
            <input type="number" name="weight">
        </label><br>

        <label>
            <span>Date of Appointment</span>
            <input type="datetime-local" name="date_of_appointment">
        </label><br>

        <label>
            <span>Gender</span>
            <input type="radio" name="gender" value="M" id="gender_male"> 
            <label for="gender_male">Male</label><br>
            
            <input type="radio" name="gender" value="F" id="gender_female"> 
            <label for="gender_female">Female</label>
        </label><br>

        <label>
            <span>Your Concerns</span>
            <textarea name="concerns" rows="3" cols="50"></textarea>
        </label><br>

        <label>
            <span>Do you have allergies?</span>
            
            <input type="radio" name="allergies" value="yes" id="allergies_yes" onclick="showTextBox()"> 
            <label for="allergies_yes">Yes</label>

            <input type="radio" name="allergies" value="no" id="allergies_no" onclick="hideTextBox()"> 
            <label for="allergies_no">No</label>

            <div id="allergiesTextBox" class="hidden">
                <label for="specified_allergies">Enter your allergies:</label>
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
            <span>Do you have Hypertension?</span>
            <input type="radio" name="hypertension" value="yes" id="hypertension_yes"> 
            <label for="hypertension_yes">Yes</label><br>
            
            <input type="radio" name="hypertension" value="no" id="hypertension_no">
            <label for="hypertension_no">No</label>
        </label>

        <label>
            <span>Do you have Diabetes?</span>
            <input type="radio" name="diabetes" value="yes" id="diabetes_yes">
            <label for="diabetes_yes">Yes</label><br>

            <input type="radio" name="diabetes" value="no" id="diabetes_no">
            <label for="diabetes_no">No</label>
        </label>

        <label>
            <span>Do you have High Uric Acid?</span>
            <input type="radio" name="uric_acid" value="yes" id="uric_acid_yes">
            <label for="uric_acid_yes">Yes</label><br>
            
            <input type="radio" name="uric_acid" value="no" id="uric_acid_no">
            <label for="uric_acid_no">No</label>
        </label>

        <label>
            <span>Do you have High Cholesterol?</span>
            <input type="radio" name="cholesterol" value="yes" id="cholesterol_yes">
            <label for="cholesterol_yes">Yes</label><br>
            
            <input type="radio" name="cholesterol" value="no" id="cholesterol_no">
            <label for="cholesterol_no">No</label>
        </label>

        <label>
            <span>Do you have Asthma?</span>
            <input type="radio" name="asthma" value="yes" id="asthma_yes">
            <label for="asthma_yes">Yes</label><br>
            
            <input type="radio" name="asthma" value="no" id="asthma_no">
            <label for="asthma_no">No</label>
        </label>

        <label>
            <span>Are you medically compromised?</span>
            <input type="radio" name="medically_compromised" value="yes" id="med_comp_yes">
            <label for="med_comp_yes">Yes (if yes, please seek clearance or approval from your medical doctor)</label><br>
            
            <input type="radio" name="medically_compromised" value="no" id="med_comp_no">
            <label for="med_comp_no">No</label>
        </label>

        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>

<?php
}else{
    header("Location: ../index.php");
    exit();
}
?>