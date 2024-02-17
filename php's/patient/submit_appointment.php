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

    }else if(empty($Weight)){
        header("Location: Request.php?error=Weight is required");
        exit();

    }else if(empty($DateOfAppointment)){
        header("Location: Request.php?error=Date of Birth is required");
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
        $Psql = "INSERT INTO `patients`(`patient_id`, `first_name`, `middle_name`, `last_name`, `last_visit`, `age`,  `gender`, `weight`, `email_address`, `contact_number`, `created_at`) 
            VALUES ('$PatientID', '$FirstName', '$MiddleName','$LastName','$LastVisit','$Age','$Gender','$Weight','$EmailAddress','$ContactNumber','$currentDateTime')
            ON DUPLICATE KEY UPDATE
            first_name = VALUES(first_name),
            middle_name = VALUES(middle_name),
            last_name = VALUES(last_name),
            last_visit = VALUES(last_visit),
            age = VALUES(age),
            gender = VALUES(gender),
            weight = VALUES(weight),
            email_address = VALUES(email_address),
            contact_number = VALUES(contact_number),
            created_at = VALUES(created_at)";

        $Presult = mysqli_query($conn, $Psql);

        $Asql = "INSERT INTO `appointments`(`patient_id`, `date_of_appointment`, `appointment_condition`, `created_at`) 
            VALUES ('$PatientID', '$DateOfAppointmentFormatted','$AppointmentCondition','$currentDateTime')";

        $result = mysqli_query($conn, $Asql);
        
        $Msql = "INSERT INTO `medical_history`(`patient_id`, `concerns`, `allergies`, `specified_allergies`, `hypertension`, `diabetes`, `uric_acid`, `cholesterol`, `asthma`, `medically_compromised`, `created_at`) 
            VALUES ('$PatientID', '$Concerns','$Allergies','$SpecifiedAllergies', '$Hypertension','$Diabetes','$UricAcid','$Cholesterol','$Asthma','$MedicallyCompromised','$currentDateTime')";

        $Mresult = mysqli_query($conn, $Msql);

        if ($Mresult && $Presult && $result  == TRUE) {
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

