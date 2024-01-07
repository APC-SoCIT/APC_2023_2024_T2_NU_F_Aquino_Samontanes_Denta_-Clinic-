<?php 
session_start(); 
include "../db_conn.php";

if (isset($_POST['submit'])) {
    $PatientID = $_SESSION['id'];
    $FirstName = $_POST['first_name'];
    $MiddleName = $_POST['middle_name'];
    $LastName = $_POST['last_name'];
    $ContactNumber = $_POST['Contact_Number'];
    $EmailAddress = $_POST['email_address'];
    $Weight = $_POST['weight'];
    $DateOfAppointment = $_POST['date_of_appointment'];
    $timestamp = strtotime($DateOfAppointment);
    $DateOfAppointmentFormatted = date('m/d/y H:i', $timestamp);
    $Gender = $_POST['gender'];
    $Concerns = $_POST['concerns'];
    $Allergies = $_POST['allergies'];
    $Hypertension = $_POST['hypertension'];
    $Diabetes = $_POST['diabetes'];
    $UricAcid = $_POST['uric_acid'];
    $Cholesterol = $_POST['cholesterol'];
    $Asthma = $_POST['asthma'];
    $MedicallyCompromised = $_POST['medically_compromised'];
    $AppointmentCondition = 'pending';

    if (empty($FirstName)) {
        header("Location: request.php?error=First Name is required");
        exit();

    }else if(empty($LastName)){
        header("Location: request.php?error=Last Name is required");
        exit();
        
    }else if(empty($ContactNumber)){
        header("Location: request.php?error=Contact Number is required");
        exit();

    }else if(empty($EmailAddress)){
        header("Location: request.php?error=Email Address is required");

    }else if(empty($Weight)){
        header("Location: request.php?error=Weight is required");
        exit();

    }else if(empty($DateOfAppointment)){
        header("Location: request.php?error=Date of Birth is required");
        exit();

    }else if(empty($Gender)){
        header("Location: request.php?error=Gender is required");
        exit();

    }else if(empty($Concerns)){
        header("Location: request.php?error=Fill your concerns regarding your teeth.");
        exit();

    }else if(empty($Allergies)){
        header("Location: request.php?error=Fill out if you have Allergies");
        exit();

    }else if(empty($Hypertension)){
        header("Location: request.php?error=Fill out if you have Hypertension");
        exit();

    }else if(empty($Diabetes)){
        header("Location: request.php?error=Fill out if you have Diabetes");
        exit();

    }else if(empty($UricAcid)){
        header("Location: request.php?error=Fill out if you have High Uric Acid");
        exit();

    }else if(empty($Cholesterol)){
        header("Location: request.php?error=Fill out if you have High Cholesterol");
        exit();

    }else if(empty($Asthma)){
        header("Location: request.php?error=Fill out if you have Asthma");
        exit();

    }else if(empty($MedicallyCompromised)){
        header("Location: request.php?error=Fill out if you are medicall compromised");
        exit();

    }else{
        $sql = "INSERT INTO `tbl_request`(`patient_id`, `first_name`, `middle_name`, `last_name`, `Contact_Number`, `email_address`, `weight`, `date_of_appointment`, `gender`, `concerns`, `allergies`, `hypertension`, `diabetes`, `uric_acid`, `cholesterol`, `asthma`, `medically_compromised`, `appointment_condition`) 
            VALUES ('$PatientID', '$FirstName','$MiddleName', '$LastName','$ContactNumber', '$EmailAddress','$Weight', '$DateOfAppointmentFormatted','$Gender', '$Concerns','$Allergies', '$Hypertension','$Diabetes','$UricAcid','$Cholesterol','$Asthma','$MedicallyCompromised','$AppointmentCondition')";

        $result = mysqli_query($conn, $sql);

        if ($result == TRUE) {
            header("Location: request.php?success=New record created succesfully");
                exit();
                if (isset($_GET['error']) || isset($_GET['error'])) {
                    unset($_GET['error']);
                    unset($_GET['success']);
                }
        } else {
            echo "Error:". $sql . "<br>". $conn->error;
        } 

        $conn->close();  
    }
        

}else{
    header("Location: request.php");
    exit();
}

