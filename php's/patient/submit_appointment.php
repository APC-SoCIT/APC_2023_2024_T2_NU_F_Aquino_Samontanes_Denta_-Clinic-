<?php 
session_start(); 
include "../../db_conn.php";

if (isset($_POST['submit'])) {
    $PatientID = $_SESSION['id'];
    $FirstName = $_POST['first_name'];
    $MiddleName = $_POST['middle_name'];
    $LastName = $_POST['last_name'];
    $LastVisit = $_POST['last_visit'];
    $Age = $_POST['age'];
    $ContactNumber = $_POST['Contact_Number'];
    $EmailAddress = $_POST['email_address'];
    $Weight = $_POST['weight'];
    $DateOfAppointment = $_POST['date_of_appointment'];
    $timestamp = strtotime($DateOfAppointment);
    $DateOfAppointmentFormatted = date('m/d/y H:i', $timestamp);
    $Gender = $_POST['gender'];
    $Concerns = $_POST['concerns'];
    $Allergies = $_POST['allergies'];
    $SpecifiedAllergies = $_POST['specified_allergies'];
    $Hypertension = $_POST['hypertension'];
    $Diabetes = $_POST['diabetes'];
    $UricAcid = $_POST['uric_acid'];
    $Cholesterol = $_POST['cholesterol'];
    $Asthma = $_POST['asthma'];
    $MedicallyCompromised = $_POST['medically_compromised'];
    $AppointmentCondition = 'pending';

    
    
    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');

    if (empty($FirstName)) {
        header("Location: Request.php?error=First Name is required");
        exit();

    }else if(empty($LastName)){
        header("Location: Request.php?error=Last Name is required");
        exit();

    }else if(empty($Age)){
        header("Location: Request.php?error=Age is required");
        exit();

    }else if(empty($LastVisit)){
        header("Location: Request.php?error=Last Visit is required");
        exit();
        
    }else if(empty($ContactNumber)){
        header("Location: Request.php?error=Contact Number is required");
        exit();

    }else if(empty($EmailAddress)){
        header("Location: Request.php?error=Email Address is required");

    }else if(empty($DateOfAppointment)){
        header("Location: Request.php?error=Date of Appointment is required");
        exit();

    }else if(empty($Gender)){
        header("Location: Request.php?error=Gender is required");
        exit();

    }else if(empty($Concerns)){
        header("Location: Request.php?error=Fill your concerns regarding your teeth.");
        exit();

    }else if(empty($Allergies)){
        header("Location: Request.php?error=Fill out if you have Allergies");
        exit();

    }else if(empty($Hypertension)){
        header("Location: Request.php?error=Fill out if you have Hypertension");
        exit();

    }else if(empty($Diabetes)){
        header("Location: Request.php?error=Fill out if you have Diabetes");
        exit();

    }else if(empty($UricAcid)){
        header("Location: Request.php?error=Fill out if you have High Uric Acid");
        exit();

    }else if(empty($Cholesterol)){
        header("Location: Request.php?error=Fill out if you have High Cholesterol");
        exit();

    }else if(empty($Asthma)){
        header("Location: Request.php?error=Fill out if you have Asthma");
        exit();

    }else if(empty($MedicallyCompromised)){
        header("Location: Request.php?error=Fill out if you are medicall compromised");
        exit();

    }else{
        // Check if patient_id already exists
        $checkQuery = "SELECT COUNT(*) as count FROM patients WHERE patient_id = '$PatientID'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $patientExists = $row['count'] > 0;
        } else {
            $patientExists = false;
        }

        // Construct SQL query
        if ($patientExists) {
            // Perform an update
            $Psql = "UPDATE patients SET
                        first_name = \"$FirstName\",
                        middle_name = \"$MiddleName\",
                        last_name = \"$LastName\",
                        last_visit = '$LastVisit',
                        age = '$Age',
                        gender = '$Gender',
                        weight = '$Weight',
                        email_address = \"$EmailAddress\",
                        contact_number = '$ContactNumber',
                        created_at = '$currentDateTime'
                    WHERE patient_id = '$PatientID'";
        } else {
            // Perform an insert
            $Psql = "INSERT INTO `patients`(`patient_id`, `first_name`, `middle_name`, `last_name`, `last_visit`, `age`, `gender`, `weight`, `email_address`, `contact_number`, `created_at`) 
                    VALUES ('$PatientID', \"$FirstName\", \"$MiddleName\",\"$LastName\", '$LastVisit', '$Age', '$Gender', '$Weight', \"$EmailAddress\", '$ContactNumber', '$currentDateTime')";
        }

        $Presult = mysqli_query($conn, $Psql);

        $Asql = "INSERT INTO `appointments`(`patient_id`, `date_of_appointment`, `appointment_condition`, `created_at`) 
            VALUES ('$PatientID', '$DateOfAppointmentFormatted','$AppointmentCondition','$currentDateTime')";

        $result = mysqli_query($conn, $Asql);

        $Usql = "UPDATE users SET
                    first_name = \"$FirstName\",
                    last_name = \"$LastName\",
                    email_address = \"$EmailAddress\"
                WHERE id = '$PatientID'";

        $Uresult = mysqli_query($conn, $Usql);
        
        // Check if medical history exists for the patient
        $checkMedicalHistoryQuery = "SELECT COUNT(*) as count FROM medical_history WHERE patient_id = '$PatientID'";
        $checkMedicalHistoryResult = $conn->query($checkMedicalHistoryQuery);

        if ($checkMedicalHistoryResult->num_rows > 0) {
            $row = $checkMedicalHistoryResult->fetch_assoc();
            $medicalHistoryExists = $row['count'] > 0;
        } else {
            $medicalHistoryExists = false;
        }

        // Construct SQL query for medical history
        if ($medicalHistoryExists) {
            // Perform an update
            $Msql = "UPDATE `medical_history` SET 
                        `concerns` = '$Concerns', 
                        `allergies` = " . ($Allergies !== null ? "'$Allergies'" : "NULL") . ",
                        `specified_allergies` = " . ($SpecifiedAllergies !== null ? "'$SpecifiedAllergies'" : "NULL") . ",
                        `hypertension` = '$Hypertension',
                        `diabetes` = '$Diabetes',
                        `uric_acid` = '$UricAcid',
                        `cholesterol` = '$Cholesterol',
                        `asthma` = '$Asthma',
                        `medically_compromised` = '$MedicallyCompromised',
                        `created_at` = '$currentDateTime' 
                    WHERE `patient_id` = '$PatientID'";
        } else {
            // Perform an insert
            $Msql = "INSERT INTO `medical_history`(`patient_id`, `concerns`, `allergies`, `specified_allergies`, `hypertension`, `diabetes`, `uric_acid`, `cholesterol`, `asthma`, `medically_compromised`, `created_at`) 
                    VALUES ('$PatientID', '$Concerns', " . ($Allergies !== null ? "'$Allergies'" : "NULL") . ", " . ($SpecifiedAllergies !== null ? "'$SpecifiedAllergies'" : "NULL") . ", '$Hypertension', '$Diabetes', '$UricAcid', '$Cholesterol', '$Asthma', '$MedicallyCompromised', '$currentDateTime')";
        }

        // Execute the SQL query for medical history
        $Mresult = mysqli_query($conn, $Msql);


        if ($Mresult && $Presult && $result && $Uresult == TRUE) {
            header("Location: Request.php?success=New record created succesfully");
                exit();
                if (isset($_GET['error']) || isset($_GET['error'])) {
                    unset($_GET['error']);
                    unset($_GET['success']);
                }
        } else {
            echo "Error:". $Psql . "<br>". $conn->error;
        }
    
        $conn->close();  
    }
        

}else{
    header("Location: Request.php");
    exit();
}

